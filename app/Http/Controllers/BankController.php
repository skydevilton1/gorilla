<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $back = Bank::get()->toarray();

        if (!empty($HrBank)) {

            for ($i = 0; $i < count($HrBank); $i++) {
                $HrBank[$i]['No'] = $i + 1;
            }
        }

        return $this->returnSuccess($back);
    }

    public function Page(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', "");

        $searchable = (new Bank)->getTableColumns();

        $table = Bank::where(
            function ($query) use ($search, $searchable) {

                if ($search) {
                    foreach ($searchable as &$s) {
                        $query->orWhere($s, 'LIKE', '%' . $search . '%');
                    }
                }
            }
        )
            ->paginate($perPage, ['*'], 'page', $page);

          if ($table->isNotEmpty()) {

            //run no
            $No = (($page - 1) * $perPage);

            for ($i = 0; $i < count($table); $i++) {

                $No = $No + 1;
                $table[$i]->No = $No;
            }
        }

        return $this->returnSuccess($table);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loginBy = $request->login_by;

        if (!isset($request->name)) {
            return $this->returnError('กรุณาระบุชื่อธนาคารให้เรียบร้อย', 404);
        } else if (!isset($request->code)) {
            return $this->returnError('กรุณาระบุรหัสธนาคารให้เรียบร้อย', 404);
        } else if (!isset($loginBy)) {
            return $this->returnError('ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่อีกครั้ง', 404);
        }

        $code = $request->code;
        $name = $request->name;

        $checkName = Bank::where(function ($query) use ($code, $name) {
            $query->orWhere('code', $code);
            $query->orWhere('name', $name);
        })
            ->first();

        if ($checkName) {
            return $this->returnError($name . ' มีข้อมูลในระบบแล้ว');
        }

        DB::beginTransaction();

        try {

            $HrBank = new Bank();
            $HrBank->code = $code;
            $HrBank->name = $name;
            $HrBank->short_name = $request->short_name;
            $HrBank->status = true;
            $HrBank->create_by = $loginBy->username;
            $HrBank->updated_at = Carbon::now()->toDateTimeString();

            $HrBank->save();

            //log
            $userId = $loginBy->username;
            $type = 'เพิ่มธนาคาร';
            $description = 'ผู้ใช้งาน ' . $userId . ' ได้ทำการ ' . $type . ' ' . $name;
            $this->Log($userId, $description, $type);
            //

            DB::commit();

            return $this->returnSuccess($HrBank);
        } catch (\Throwable $e) {

            DB::rollback();

            return $this->returnError('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank = Bank::find($id);
        return $this->returnSuccess($bank);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loginBy = $request->login_by;

        if (!Bank::where('id', $id)->exists()) {
            return $this->returnError('ไม่พบข้อมูล id');
        } else if (!isset($request->status)) {
            return $this->returnError('กรุณาระบุข้อมูล status');
        } else if (!isset($loginBy)) {
            return $this->returnError('ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่อีกครั้ง');
        }

        $code = $request->code;
        $name = $request->name;
        $short_name = $request->short_name;

        $checkName = Bank::where(function ($query) use ($code, $name, $short_name) {
            $query->orWhere('code', $code);
            $query->orWhere('name', $name);
        })
            ->where('id', '!=', $id)
            ->first();

        if ($checkName) {
            return $this->returnError($name . ' มีข้อมูลในระบบแล้ว');
        }

        DB::beginTransaction();

        try {

            $HrBank = Bank::find($id);
            $HrBank->code = $code;
            $HrBank->name = $name;
            $HrBank->short_name = $short_name;
            $HrBank->status = $request->status;
            $HrBank->update_by = $loginBy->username;
            $HrBank->updated_at = Carbon::now()->toDateTimeString();

            $HrBank->save();

            //log
            $userId = $loginBy->username;
            $type = 'แก้ไขธนาคาร';
            $description = 'ผู้ใช้งาน ' . $userId . ' ได้ทำการ ' . $type . ' ' . $HrBank->name;
            $this->Log($userId, $description, $type);
            //

            DB::commit();

            return $this->returnSuccess($HrBank);
        } catch (\Throwable $e) {

            DB::rollback();

            return $this->returnError('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($request, $id)
    {
        $loginBy = $request->login_by;

        if (!isset($loginBy)) {
            return $this->returnError('ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่อีกครั้ง');
        }

        DB::beginTransaction();

        try {

            $HrBank = Bank::find($id);
            if ($HrBank) $HrBank->delete();
            else return $this->returnError('ไม่พบข้อมูล id');

            //log
            $userId = $loginBy->username;
            $type = 'ลบธนาคาร';
            $description = 'ผู้ใช้งาน ' . $userId . ' ได้ทำการ ' . $type . ' ' . $HrBank->name;
            $this->Log($userId, $description, $type);

            DB::commit();

            return $this->returnSuccess('ดำเนินการสำเร็จ');
        } catch (\Throwable $e) {

            DB::rollback();

            return $this->returnError('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง ' . $e->getMessage());
        }
    }
}
