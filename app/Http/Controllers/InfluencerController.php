<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfluencerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            return $this->returnError('กรุณาระบุ Name Account', 404);
        } else if (!isset($request->tel)) {
            return $this->returnError('กรุณาระบุหมายเลขโทรศัพท์', 404);
        } else if (!isset($request->occupation)) {
            return $this->returnError('กรุณาระบุอาชีพ', 404);
        } else if (!isset($request->email)) {
            return $this->returnError('กรุณาระบุอีเมล', 404);
        } else if (!isset($request->line)) {
            return $this->returnError('กรุณาระบุ ID Line', 404);
        } else if (!isset($request->type)) {
            return $this->returnError('กรุณาระบุ Lifestyle', 404);
        } else if (!isset($request->birthday)) {
            return $this->returnError('กรุณาระบุ วัน/เดือน/ปีเกิด', 404);
        }

        $checkUserId = Influencer::where('name', $request->name)->first();
            if ($checkUserId) {
                return $this->returnError('มีชื่อบัญชีผู้ใช้งาน ' . $request->username . ' ในระบบแล้ว', 404);
            }

            DB::beginTransaction();

            try {

                $User = new Influencer();
                $User->name = $request->name;
                $User->sex = $request->sex;
                $User->tel = $request->tel;
                $User->occupation = $request->occupation;
                $User->email = $request->email;
                $User->line = $request->line;
                $User->type = $request->type;
                $User->birthday = $request->birthday;
                $User->current_address = $request->current_address;
                $User->bank_name = $request->bank_name;
                $User->bank_no = $request->bank_no;
                $User->citizen_name = $request->citizen_name;
                $User->citizen_no = $request->citizen_no;
                $User->citizen_address = $request->citizen_address;
                $User->bank_id = $request->bank_id;
                $User->provinces_id = $request->provinces_id;

                if ($request->copy_card && $request->copy_card != null && $request->copy_card != 'null') {
                    $User->copy_card = $this->uploadImage($request->copy_card, '/images/copycard/');
                }
                if ($request->copy_back && $request->copy_back != null && $request->copy_back != 'null') {
                    $User->copy_back = $this->uploadImage($request->copy_back, '/images/copyback/');
                }
                $User->ex_work = $request->ex_work;
                $User->link_tiktok = $request->link_tiktok;
                $User->name_tiktok = $request->name_tiktok;
                $User->fol_tiktok = $request->fol_tiktok;

                $User->link_facebook = $request->link_facebook;
                $User->name_facebook = $request->name_facebook;
                $User->fol_facebook = $request->fol_facebook;

                $User->link_ig = $request->link_ig;
                $User->name_ig = $request->name_ig;
                $User->fol_ig = $request->fol_ig;

                $User->link_youtube = $request->link_youtube;
                $User->name_youtube = $request->name_youtube;
                $User->fol_youtube = $request->fol_youtube;

                $User->link_twitter = $request->link_twitter;
                $User->name_twitter = $request->name_twitter;
                $User->fol_twitter = $request->fol_twitter;

                $User->link_blog = $request->link_blog;
                $User->name_blog = $request->name_blog;
                $User->fol_blog = $request->fol_blog;

                $User->status = 1;
                $User->create_by = $loginBy->name;

                $User->save();

                DB::commit();

                return $this->returnSuccess('ดำเนินการสำเร็จ', $User);
            }
            catch (\Throwable $e) {

                DB::rollback();

                return $this->returnError('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง ' . $e, 404);
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
        //
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

        $id = $request->id;
        $loginBy = $request->login_by;

        if (!isset($id)) {
            return $this->returnErrorData('ไม่พบข้อมูล id', 404);
        } else  if (!isset($request->status)) {
            return $this->returnErrorData('ไม่พบข้อมูล status', 404);
        } else if (!isset($loginBy)) {
            return $this->returnErrorData('ไม่พบข้อมูลเผู้ใช้ กรุณาเข้าสู่ระบบใหม่อีกครั้ง', 404);
        }

        DB::beginTransaction();

        try {

                $User = Influencer::find($id);

                $User = new Influencer();
                $User->name = $request->name;
                $User->sex = $request->sex;
                $User->tel = $request->tel;
                $User->occupation = $request->occupation;
                $User->email = $request->email;
                $User->line = $request->line;
                $User->type = $request->type;
                $User->birthday = $request->birthday;
                $User->current_address = $request->current_address;
                $User->bank_name = $request->bank_name;
                $User->bank_no = $request->bank_no;
                $User->citizen_name = $request->citizen_name;
                $User->citizen_no = $request->citizen_no;
                $User->citizen_address = $request->citizen_address;

                if ($request->copy_card && $request->copy_card != null && $request->copy_card != 'null') {
                    $User->copy_card = $this->uploadImage($request->copy_card, '/images/copycard/');
                }
                if ($request->copy_back && $request->copy_back != null && $request->copy_back != 'null') {
                    $User->copy_back = $this->uploadImage($request->copy_back, '/images/copyback/');
                }

                $User->ex_work = $request->ex_work;
                $User->link_tiktok = $request->link_tiktok;
                $User->name_tiktok = $request->name_tiktok;
                $User->fol_tiktok = $request->fol_tiktok;

                $User->link_facebook = $request->link_facebook;
                $User->name_facebook = $request->name_facebook;
                $User->fol_facebook = $request->fol_facebook;

                $User->link_ig = $request->link_ig;
                $User->name_ig = $request->name_ig;
                $User->fol_ig = $request->fol_ig;

                $User->link_youtube = $request->link_youtube;
                $User->name_youtube = $request->name_youtube;
                $User->fol_youtube = $request->fol_youtube;

                $User->link_twitter = $request->link_twitter;
                $User->name_twitter = $request->name_twitter;
                $User->fol_twitter = $request->fol_twitter;

                $User->link_blog = $request->link_blog;
                $User->name_blog = $request->name_blog;
                $User->fol_blog = $request->fol_blog;

                $User->status = $request->status;
                $User->create_by = $loginBy->name;

                $User->save();
            //

            DB::commit();

            return $this->returnSuccess('ดำเนินการสำเร็จ',  $User);
        } catch (\Throwable $e) {

            DB::rollback();

            return $this->returnErrorData('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง ' . $e, 404);
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
        //dd($loginBy);
        if (!isset($loginBy)) {
            return $this->returnErrorData('[login_by] ไม่มีข้อมูล', 404);
        }

        DB::beginTransaction();

        try {

            $User = Influencer::find($id);


            $User->delete();
            // $Position->delete();

            DB::commit();

            return $this->returnUpdate('ดำเนินการลบสำเร็จ');
        } catch (\Throwable $e) {

            DB::rollback();

            return $this->returnErrorData('ดำเนินการลบUserผิดพลาด ' . $e, 404);
        }
    }
}
