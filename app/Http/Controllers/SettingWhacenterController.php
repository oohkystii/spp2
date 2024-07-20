<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Services\WaBlasService;
use App\Services\WhacenterService;
use Illuminate\Http\Request;
use Session;

class SettingWhacenterController extends Controller
{

    static string $token;
    static string $baseUrl;

    public function __construct()
    {
        self::$token = Token::first()->token ?? '';
        self::$baseUrl = $_ENV['BASE_URL_API'];
    }

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
        $statusKoneksiWa = false;
        $error = false;
        $deviceInfo = null;
        try {
            $wablas = new WaBlasService();
            $deviceInfo = $wablas->getDeviceInfo();
            $statusKoneksiWa = $deviceInfo->status == 'connected';
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            $statusKoneksiWa = false;
        }
        return view('operator.settingwhacenter_form', [
            'statusKoneksiWa' => $statusKoneksiWa,
            'deviceInfo' => $deviceInfo,
            'error' => $error,
            'url' => self::$baseUrl . "/device/scan?token=" . self::$token,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('number') && $request->number != '') {
            $ws = new WaBlasService();
            $statusSend = $ws->sendSingleMessage($request->number, $request->message);
            if ($statusSend) {
                flash('Pesan sudah dikirim');
                return back();
            }
            flash()->addError('Data gagal dikirim');
            return back();
        }
        $dataSettings = $request->except('_token');
        settings()->set($dataSettings);

        Session::flash('success', 'WhatsApp terhubung');
        return redirect()->back();
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

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function disconnect()
    {
        $wablas = new WaBlasService();
        $result = $wablas->disconnectDevice();
        if ($result) {
            Session::flash('success', 'WhatsApp terputus');
            return redirect()->back();
        } else {
            Session::flash('error', 'WhatsApp gagal terputus');
            return redirect()->back();
        }
    }
}
