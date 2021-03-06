<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $courts = array(
            // 'services-ecourts-gov-in.py' => 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_orderdate.php?state=D&state_cd=26&dist_cd=8',
            'main-sci-gov-in-daily-order.py' => 'https://main.sci.gov.in/daily-order',
            'delhihighcourt-nic-in-case.py' => 'http://delhihighcourt.nic.in/case.asp',
            '164-100-69-66-jsearch.py' => 'http://164.100.69.66/jsearch/',
            'main-sci-gov-in-judgments.py' => 'https://main.sci.gov.in/judgments'

        );
        return view('Home.index',compact('courts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        try{
            $validate  = validator($request->all(),[
                'fdate' => 'required',
                'tdate' => 'required',
                'court' => 'required'
            ],[
                'fdate.required' => 'From date is required',
                'tdate.required' => 'To date is required',
                'court.required' => 'Court is required'
            ]);

            if($validate->fails()){
                return redirect()->back()->withErrors($validate->errors()->first());
            }
            $courts = array(
                // 'services-ecourts-gov-in.py' => 'https://services.ecourts.gov.in/ecourtindia_v4_bilingual/cases/s_orderdate.php?state=D&state_cd=26&dist_cd=8',
                'main-sci-gov-in-daily-order.py' => 'https://main.sci.gov.in/daily-order',
                'delhihighcourt-nic-in-case.py' => 'http://delhihighcourt.nic.in/case.asp',
                '164-100-69-66-jsearch.py' => 'http://164.100.69.66/jsearch/',
                'main-sci-gov-in-judgments.py' => 'https://main.sci.gov.in/judgments'

            );
            $fileName = $request->court;

            $create = \App\Base::create([
                'link' => $courts[$fileName],
                'frm_date' => $request->fdate,
                'to_date' => $request->tdate
            ]);

            // $process = new Process(['python3.6', base_path('python/supreme.py'),'-id',$create->id]);
            $process = new Process(['python3', base_path('python/'.$fileName),'-id',$create->id]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();
        }catch(\Exception $exception){
            return redirect()->back()->withErrors($exception->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
