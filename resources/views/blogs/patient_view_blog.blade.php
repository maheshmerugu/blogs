@extends('layouts.header')

@section('content')

<div class="Auricle_Blogs">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="breadcrum">
                        <ul>
                            <li>Arucile Blog</li>
                            <li><i class="fa fa-angle-right"></i></li>
                            <li>Patient Blog</li>
                            <li><i class="fa fa-angle-right"></i></li>
                            <li>Patient Blog View</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="over-view">
        <div class="container">
            <div class="row">
                <div class="col-md-7 m-auto">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="images/thumbnail.webp" class="img-fluid" alt="thumnb" />
                        </div>
                        <div class="col-md-8">
                            <p class="topic">Atopic Dermatitis</p>

                            <h4 class="name">{{$patient_blog->expert_name ?? ''}}</h4>
                            <p class="disignation">{{$patient_blog->diagnosizing_from ?? ''}}</p>
                            <p>Vineet Khanna, MD, has had eczema for as long as he can remember. A musculoskeletal radiologist, Khanna has a keen interest in health care technology and medical research. In addition to clinical work, he serves as the chief medical officer of inference analytics and is researching novel ways to integrate pollution data science with clinical medicine. You can connect with him on LinkedIn.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <h2>Latest Patient Blog Posts</h2>
                </div>
                <div class="Viewall"> 
                    <a href="{{ route('allpatientexperts.view') }}" class="btn btn-light"><span>View All <i class="fa-solid fa-arrow-right"></i></span></a>
                </div>
            </div>

            <div class="row mt-3">
            @foreach($blogs as $blog)

                <div class="col-md-4 mb-3">
                    <div class="card cardBlog">
                        <img src="images/blog1.png" class="img-fluid">
                        <div class="card-body">

                            <div class="cardText">
                                <h3>{{$blog->title ?? ''}}</h3>
                                <p> {{strip_tags($blog->description ?? '')}}</p>
                                <p class="bycandidate"> By <span> Rachael Estes </span> </p>
                                <p class="mb-0"> January 2, 2024 </p>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
                
            </div>
        </div>
    </section>


@endsection