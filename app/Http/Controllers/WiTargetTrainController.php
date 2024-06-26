<?php

namespace App\Http\Controllers;

use App\Http\Requests\WiTargetTrainRequest;
use App\Services\WiTargetTrainService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WiTargetTrainController extends Controller
{
    protected WitargetTrainService $wiTargetTrainService;

    public function __construct(WiTargetTrainService $wiTargetTrainService)
    {
        $this->wiTargetTrainService = $wiTargetTrainService;
    }

    public function convertDateTime($value): string
    {
        return Carbon::createFromFormat('Y/m', $value)->startOfMonth();
    }

    public function ListTargetTrain($year, $month, $cust_id): JsonResponse
    {
        try {
            $target_month = $year . '/' . $month;
            $target_month = $this->convertDateTime($target_month);
            $TargetTrains = $this->wiTargetTrainService->getWiTargetTrain($target_month, $cust_id);
            return response()->json([
                'message' => 'Success',
                'TargetTrains' => $TargetTrains
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'TargetTrains' => [],
            ], 400);
        }
    }

    public function create(WiTargetTrainRequest $request,checkMonthController $checkMonthController): JsonResponse
    {
        $TargetTrain = $request->all();
        $target_month = Carbon::parse($this->convertDateTime($TargetTrain['target_month']))->startOfMonth();
        $TargetTrain['target_month'] = $this->convertDateTime($TargetTrain['target_month']);
        $check = $checkMonthController->checkTargetMonth($target_month);
        if(!$check['status']){
            return response()->json([
                'message' => $check['desc']
            ], 400);
        }

        $TargetTrain = $this->wiTargetTrainService->create($TargetTrain);
        return response()->json([
            'message' => $TargetTrain ? 'บันทึกข้อมูลเสร็จสิ้น' : 'บันทึกข้อมูลไม่สำเร็จ',
        ], $TargetTrain ? 200 : 400);

    }

    public function update($id,$year,$month, Request $request,checkMonthController $checkMonthController): JsonResponse
    {
        $target_month = $year.'/'.$month;
        $target_month = Carbon::parse($this->convertDateTime($target_month))->startOfMonth();
        $check = $checkMonthController->checkTargetMonth($target_month);
        if(!$check['status']){
            return response()->json([
                'message' => $check['desc']
            ], 400);
        }
        $request->validate([
            'desc' => 'required'
        ],[
            'desc.required' => 'กรุณาระบุคำอธิบายการฝึกอบรม'
        ]);
        $TargetTrainUpdate = $this->wiTargetTrainService->update($id, $request['desc']);
        return response()->json([
            'message' => $TargetTrainUpdate ? 'บันทึกสำเร็จ' : 'บันทึกไม่สำเร็จ',
        ], $TargetTrainUpdate ? 200 : 400);
    }

    public function delete($id,$year,$month,checkMonthController $checkMonthController): JsonResponse
    {
        $target_month = $year.'/'.$month;
        $target_month = Carbon::parse($this->convertDateTime($target_month))->startOfMonth();
        $check = $checkMonthController->checkTargetMonth($target_month);
        if(!$check['status']){
            return response()->json([
                'message' => $check['desc']
            ], 400);
        }
        $TargetTrainDelete = $this->wiTargetTrainService->delete($id);
        return response()->json([
            'message' => $TargetTrainDelete ? 'ลบสำเร็จ' : 'ลบไม่สำเร็จ'
        ], $TargetTrainDelete ? 200 : 400);
    }
}
