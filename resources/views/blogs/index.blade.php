@extends('layouts.header')

@section('content')
<section class="section container">
        <div class="carouselMain">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <h2>Featured Patient and Expert Contributors</h2>
                </div>
                <div class="Viewall">
                    <a href="{{ route('allpatientexperts.view') }}" class="btn btn-success-outline">View All <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="variable-width">

            @foreach($patient_experts as $expert)
                <div class="carosel1">
                    <div class="imgperson">
                        <img src="images/categoryimg.png" class="img-fluid">
                    </div>
                    <div class="imgText">
                        <div class="category">
                            <p class="username">{{$expert->expert_name}}</p>
                            <p class="tag">{{$expert->getTitleByCategoryId($expert->category_id)}}</p>
                        </div>
                        <p class="diagnosed">{{$expert->diagnosizing_from}}</p>
                        <p class="persondescri">Zainab Alani was diagnosed with generalized myasthenia gravis (MG)
                            at age 15. She had...
                        </p>

                        <a href="{{ route('patientblog.view', ['id' => $expert->id]) }}" class="seemore">See More <i class="fa-solid fa-arrow-right"></i> </a>
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
                    <h2>Latest Patient Blog Posts</h2>
                </div>
                <div class="Viewall"> 
                    <a href="{{ route('allpatientexperts.view') }}" class="btn btn-light"><span>View All <i class="fa-solid fa-arrow-right"></i></span></a>
                </div>
            </div>

            <div class="row mt-3">
            @foreach($blogs as $blog)


                <div class="col-md-4 mb-3">
                    <a   href="{{ route('blog.view', ['id' => $expert->id]) }}">
                        
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
                    </a>
                </div>

                @endforeach
                
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <h2>Post your Article</h2>
            <div class="card articleForm">
                <form id="myForm">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <form method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="text" name="name" placeholder=" Full Name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="text" name="email" placeholder=" Email ID" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <input type="text" name="phone" placeholder=" Phone Number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="location" placeholder=" Location" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <textarea cols="" rows="4" name="article"  placeholder="Your article..." class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3 text-end">
                                        <button class="btn btn-submit" type="button" onclick="submitForm()">Submit</button>

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

                </form>
               
            </div>
        </div>
    </section>




    <script>
    function submitForm() {
        $.ajax({
            type: 'POST',
            url: '{{ route("article.submit") }}', // Use the route helper to generate the URL
            data: $('#myForm').serialize() + "&_token={{ csrf_token() }}", // Include CSRF token
            success: function (data) {
                console.log(data);
                toastr.success(data.message);
                $('#myForm')[0].reset(); // Reset the form using JavaScript
                // Handle success response
            },
            error: function (error) {
                console.log(error);
                // Handle error response
                if (error.responseJSON && error.responseJSON.errors) {
                    // Loop through the errors and display each one using Toastr
                    $.each(error.responseJSON.errors, function (key, value) {
                        toastr.error(value);
                    });
                } else {
                    // Display a generic error message if no specific error messages are returned
                    toastr.error('An error occurred while submitting the form');
                }
            }
        });
    }
</script>


@endsection 