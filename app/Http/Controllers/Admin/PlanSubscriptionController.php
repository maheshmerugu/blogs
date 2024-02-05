<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlanSubscriptionController extends Controller
{
    //Plan List View
    public function getPlanView()
    {
        return view('admin.plan-subscriptions.plan_list');
    }
    //Plan List AJAX
    public function getPlanLists(Request $request)
    {
        Log::info('Blog Lists Request Data: ' . json_encode($request->all()));

        if (isset($_GET['search']['value'])) {
            $search = $_GET['search']['value'];
        } else {
            $search = '';
        }
        if (isset($_GET['length'])) {
            $limit = $_GET['length'];
        } else {
            $limit = 10;
        }
        if (isset($_GET['start'])) {
            $offset = $_GET['start'];
        } else {
            $offset = 0;
        }
        $orderType = $_GET['order'][0]['dir'];
        $nameOrder = $_GET['columns'][$_GET['order'][0]['column']]['name'];
        $total = PlanSubscription::count();
        $planSubscription = PlanSubscription::where('plan_name', 'like', '%' . $search . '%')
            ->offset($offset)->limit($limit)
            ->orderBy('id', $orderType)
            ->get();

        $i = 1 + $offset;
        $data = [];
        foreach ($planSubscription as $key => $planData) {

            $data[] = array(
                $key + 1,
                $planData->plan_name,
                $planData->watch_hours,
                $planData->months,
                $planData->amount,

                ' <a href="javascript::void(0)" class="deletePlan" data-id="' . $planData->id . '"><i class="fa fa-trash text-danger"></i></a>| <a href="' . route('admin.plan.detail', [$planData->id]) . '" class="detailPlan" data-detail-id="' . $planData->id . '"><i class="fa fa-eye"></i></a>| <a href="' . route('admin.plan.edit', [$planData->id]) . '" class="editPlan" data-detail-id="' . $planData->id . '"><i class="fa fa-edit"></i></a>',
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        return response()->json($records);
    }

    public function index()
    {
        return view('admin.plan-subscriptions.add');
    }
    //Store plans
    public function create(Request $request)
    {
        $rule = [
            'amount' => "required",
            'months' => "required",
            'plan_name' => "required",
        ];

        $custom = [
            'plan_name.required' => 'Plan name is required',
            'months.required' => 'Months is required',
            'amount.required' => 'Amount is required',

        ];
        $validator = Validator::make($request->all(), $rule, $custom);
        if ($validator->fails()) {
            $this->sendWebResponse(false, $validator->errors()->first());
        }
        try {

            $plan = new PlanSubscription();
            $plan->plan_name = $request->plan_name;
            $plan->months = $request->months;
            $plan->access_to_video = $request->access_to_video ?? 0;
            $plan->access_to_notes = $request->access_to_notes ?? 0;
            $plan->access_to_question_bank = $request->access_to_question_bank ?? 0;
            $plan->amount = $request->amount;
            $plan->discount = $request->discount;
            $plan->watch_hours = $request->watch_hours;
            $plan->payble_amount = $request->payble_amount;
            $plan->plan_type = $request->plan_type;
            $insert = $plan->save();

            if ($insert) {
                $this->sendWebResponse(true, "Successfully Added");
            } else {
                $this->sendWebResponse(false, "Something went wrong, please try again");
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    //Delete Media News
    public function deletePlan(Request $request)
    {

        try {
            $plan = PlanSubscription::findOrFail($request->plan);

            $delete = $plan->delete();

            if ($delete) {
                $this->sendWebResponse(true, "Successfully Deleted");
            } else {
                $this->sendWebResponse(false, "Something went wrong, please try again");
            }
        } catch (Exception $e) {

            $this->sendWebResponse(false, "Something went wrong, please try again");
        }
    }

    public function planEditView(Request $request)
    {
        $plan = PlanSubscription::findOrFail($request->plan_id);

        $data = [
            'plan' => $plan,
        ];

        return view('admin.plan-subscriptions.edit', $data);
    }
    //Update Plan
    public function updatePlan(Request $request)
    {

        $rule = [
            'amount' => "required",
            'months' => "required",
            'plan_name' => "required",
        ];

        $custom = [
            'plan_name.required' => 'Plan name is required',
            'months.required' => 'Months is required',
            'amount.required' => 'Amount is required',

        ];
        $validator = Validator::make($request->all(), $rule, $custom);
        if ($validator->fails()) {
            $this->sendWebResponse(false, $validator->errors()->first());
        }

        $plan = PlanSubscription::find($request->id);
        $plan->plan_name = $request->plan_name;
        $plan->months = $request->months;
        $plan->access_to_video = $request->access_to_video ?? 0;
        $plan->access_to_notes = $request->access_to_notes ?? 0;
        $plan->access_to_question_bank = $request->access_to_question_bank ?? 0;
        $plan->amount = $request->amount;
        $plan->discount = $request->discount;
        if ($request->access_to_video == 0) {
            $plan->watch_hours = 0;
        } else {
            $plan->watch_hours = $request->watch_hours;
        }
        $plan->payble_amount = $request->payble_amount;
        $plan->plan_type = $request->plan_type;
        $insert = $plan->save();

        $update = $plan->update();

        if ($update) {
            $this->sendWebResponse(true, "Successfully Updated");
        } else {
            $this->sendWebResponse(false, "Something went wrong, please try again");
        }

    }
    //Plan detail view
    public function planDetail(Request $request)
    {
        $plan = PlanSubscription::findOrFail($request->plan_id);
        $data = [
            'plan' => $plan,
        ];

        return view('admin.plan-subscriptions.view', $data);

    }
}
