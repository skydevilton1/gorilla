<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\prefix;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PrefixController extends Controller
{

    public function index()
    {
        $hrPrefix = prefix::get()->toarray();

        if (!empty($hrPrefix)) {

            for ($i = 0; $i < count($hrPrefix); $i++) {
                $hrPrefix[$i]['No'] = $i + 1;
            }
        }

        return $this->returnSuccess($hrPrefix);
    }

    public function Page(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', "");

        $searchable = (new prefix())->getTableColumns();

        $table = prefix::where(
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
        $code = $request->code;
        $name = $request->name;
        $short_name = $request->short_name;


        DB::beginTransaction();

        try {

            $hrPrefix = new prefix();
            $hrPrefix->code = $code;
            $hrPrefix->name = $name;
            $hrPrefix->short_name = $short_name;


            $hrPrefix->status = true;
            $hrPrefix->create_by = $loginBy->username;

            $hrPrefix->updated_at = Carbon::now()->toDateTimeString();

            $hrPrefix->save();

           

            DB::commit();

            return $this->returnSuccess($hrPrefix);
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
        $hrPrefix = prefix::find($id);
        return $this->returnSuccess($hrPrefix);
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
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::unique('hr_prefixes')->ignore($id),
            ],
            'name' => [
                'required',
                Rule::unique('hr_prefixes')->ignore($id),
            ],
            'gender' => 'required|in:M,F',
            'status' => 'required',
        ], [
            'code.required' => 'กรุณาระบุรหัสคำนำหน้า',
            'code.unique' => 'รหัสคำนำหน้านี้ถูกใช้งานแล้ว',
            'name.required' => 'กรุณาระบุคำนำหน้า',
            'name.unique' => 'คำนำหน้านี้ถูกใช้งานแล้ว',
            'gender.required' => 'กรุณาระบุเพศ',
            'gender.in' => 'เพศไม่ถูกต้อง',
            'status.required' => 'กรุณาระบุข้อมูล status',
        ]);

        // if ($validator->fails()) {
        //     $errors = $validator->errors();
        //     return $this->returnError($errors->first(), $errors);
        // }

        $loginBy = $request->login_by;
        $code = $request->code;
        $name = $request->name;
        $short_name = $request->short_name;


        DB::beginTransaction();

        try {

            $hrPrefix = prefix::find($id);
            $hrPrefix->code = $code;
            $hrPrefix->name = $name;
            $hrPrefix->short_name = $short_name;
            $hrPrefix->status = $request->status;

            $hrPrefix->update_by = $loginBy->username;
            $hrPrefix->updated_at = Carbon::now()->toDateTimeString();

            $hrPrefix->save();

            //log
            $userId = $loginBy->username;
            $type = 'แก้ไขคำนำหน้า';
            $description = 'ผู้ใช้งาน ' . $userId . ' ได้ทำการ ' . $type . ' ' . $hrPrefix->name;
            $this->Log($userId, $description, $type);
            //

            DB::commit();

            return $this->returnSuccess($hrPrefix);
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
    public function destroy(Request $request, $id)
    {
        $loginBy = $request->login_by;

        if (!isset($loginBy)) {
            return $this->returnError('ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่อีกครั้ง');
        }

        DB::beginTransaction();

        try {

            $hrPrefix = prefix::find($id);
            if ($hrPrefix) $hrPrefix->delete();
            else return $this->returnError('ไม่พบข้อมูล id');

            //log
            $userId = $loginBy->username;
            $type = 'ลบคำนำหน้า';
            $description = 'ผู้ใช้งาน ' . $userId . ' ได้ทำการ ' . $type . ' ' . $hrPrefix->name;
            $this->Log($userId, $description, $type);

            DB::commit();

            return $this->returnSuccess('ดำเนินการสำเร็จ');
        } catch (\Throwable $e) {

            DB::rollback();

            return $this->returnError('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง ' . $e->getMessage());
        }
    }
}
