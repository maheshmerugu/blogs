@extends('layouts.header')

@section('content')
<div class="Auricle_Blogs">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="Auricle_BlogsTxt">
                        <h1>Patient Contributors Directory</h1>
                        <p>Meet the people sharing what it's really like to<br>live with a chronic condition</p>
                    </div>
                </div>
                <div class="col-md-5" style="mix-blend-mode: multiply">
                    <img src="images/blog-hero.png" class="img-fluid" alt="Auricle Blog" />
                </div>
            </div>
        </div>
    </div>

    <section class="section container">
        <div class="container patients">
            <div class="row">
            @foreach($all_patient_experts as $expert)

                <div class="col-md-3 mb-3">
                    <div class="card card-body text-center">
                        <div class="thumbnail">
                            <img src="images/thumbnail.webp" class="img-fluid" alt="thumbnail"/>
                        </div>
                        <h5>{{$expert->expert_name ?? ''}}</h5>
                        <p>{{$expert->diagnosizing_from ?? ''}}</p>
                        <a href="patient-blog-view.html">See More <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>

                @endforeach
              
            </div>
        </div>
    </section>





@endsection