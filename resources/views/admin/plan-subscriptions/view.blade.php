@extends('admin.layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('admin/dropify/css/dropify.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="{{  route('admin.plans.list.view')  }}"> Plan Subscription list </a>/ Plan Details</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <!-- <form method="post" action="" id="myForm" enctype="multipart/form-data"> -->
                        @csrf

                        
                       
                        <div class="transaction_listing">
                            <ul>
                               
                                <li><strong>Plan name :</strong> <p>{{$plan->plan_name}}</p></li>
                                <li><strong>Months :</strong> <p>{{ $plan->months }}</p></li>
                                <li><strong>Watch hours :</strong> <p>{{ $plan->watch_hours }}</p></li>
                                <li><strong>Plan Type :</strong> @if($plan->plan_type == 1)<p>Video</p>
                                @else
                                <p>Notes</p></li>
                                @endif
                                 <li><strong>Access to video :</strong> @if($plan->access_to_video == 1)<p>Yes</p>
                                @else
                                <p>No</p></li>
                                @endif
                                <li><strong>Access to notes :</strong> @if($plan->access_to_notes == 1)<p>Yes</p>
                                @else
                                <p>No</p></li>
                                @endif
                                <li><strong>Access to question bank:</strong> @if($plan->access_to_question_bank == 1)<p>Yes</p>
                                @else
                                <p>No</p></li>
                                @endif
                                <li><strong>Amount :</strong> <p>{{ $plan->amount }}</p></li>
                                <li><strong>Discount :</strong> <p>{{ $plan->discount }}</p></li>
                                <li><strong>Payble amount :</strong> <p>{{ $plan->payble_amount }}</p></li>
                            
                              
                                
                               
                            </ul>
                        </div>
                        
                        
                        
                </div>
            </div>
        </div>
    </div>
</div>







@endsection
