<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Order;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends BaseController
{

    public function index()
    {
        //
    }

    public function eod()
    {
        $date = $this->getSystemDate();
        return view('admin.end_of_day', compact('date'));
    }

    public function runEod()
    {
        $setting = $this->getSystemSetting();
        if($setting->system_date>now() ){
            return redirect()->back()->with(['error' => 'Invalid day.']);
        }
        $systemDate = $setting->system_date;
        $this->updateMovement($systemDate);
        $setting->system_date = $setting->system_date->addDay();
        $setting->update();

        Session::put('system_date', $setting->system_date);
        return redirect()->back()->with(['message' => 'End of day successfully done.']);
    }

    private function updateMovement($sy_date)
    {
//        dd($sy_date);
        $date=$sy_date;
        $orders = Order::where('system_date', '=', $sy_date->format('Y-m-d'))->get();
        $previousDate=$date->addDay(-1);
//        dd($orders);
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                foreach ($item->menu->menuItems as $menuItem) {
                    $m = Movement::with('product')
                        ->where([
                            ['product_id', '=', $menuItem->product_id],
                            ['system_date', '=', $previousDate->format('Y-m-d')]
                        ])
                        ->orderByDesc('id')->limit(1)->first();
                   if($m!=null){
                       $mov=new Movement();
                       $mov->opening=$m->closing;
                       $mov->closing=$menuItem->product->qty;
                       $mov->product_id=$menuItem->product_id;
                       $mov->system_date=$order->system_date;
                       $mov->save();
                   }else{
                       $mov=new Movement();
                       $mov->opening=$menuItem->product->original_qty;
                       $mov->closing=$menuItem->product->qty;
                       $mov->product_id=$menuItem->product_id;
                       $mov->system_date=$order->system_date;
                       $mov->save();
                   }
                }
            }
        }
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }


}
