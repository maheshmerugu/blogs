@extends('layouts.header')

@section('content')
<section class="section container">
        <div class="carouselMain">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <h2>Featured Patient and Expert Contributors</h2>
                </div>
                <div class="Viewall">
                    <a href="patient-blog.html" class="btn btn-success-outline">View All <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="variable-width">

            @foreach($categories as $category)
                <div class="carosel1">
                    <div class="imgperson">
                        <img src="images/categoryimg.png" class="img-fluid">
                    </div>
                    <div class="imgText">
                        <div class="category">
                            <p class="username">Zainab Alani</p>
                            <p class="tag">{{$category->name ?? ''}}</p>
                        </div>
                        <p class="diagnosed">Diagnosed since 2019</p>
                        <p class="persondescri">Zainab Alani was diagnosed with generalized myasthenia gravis (MG)
                            at age 15. She had...
                        </p>

                        <p class="seemore">See More <i class="fa-solid fa-arrow-right"></i> </p>
                    </div>
                </div>

             @endforeach

             

                

                
            </div>
        </div>

    </section>


    <section>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <h2>Latest Food Blog Posts</h2>
                </div>
                <div class="Viewall">
                    <a href="" class="btn btn-light"><span>View All <i class="fa-solid fa-arrow-right"></i></span></a>
                </div>
            </div>

            <div class="row mt-3">
            @foreach($blog_posts as $post)

                <div class="col-md-4 mb-3">
                    <div class="card cardBlog">
                        <img src="images/blog1.png" class="img-fluid">
                        <div class="card-body">

                            <div class="cardText">
                                <h3>{{$post->getTitleByCategoryId($post->category_id)}}</h3>
                                <p> {{$post->getDescByCategoryId($post->category_id)}}</p>
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

    <section>
        <div class="container">
            <h2>Post your Article</h2>
            <div class="card articleForm">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="text" placeholder=" Full Name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="text" placeholder=" Email ID" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="text" placeholder=" Phone Number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" placeholder=" Location" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <textarea cols="" rows="4" placeholder="Your article..." class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3 text-end">
                                            <button class="btn btn-submit"> Submit </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="col-md-4">
                            <img src="images/formimg.png" class="formimg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection 