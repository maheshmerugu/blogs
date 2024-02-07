<div class="table-responsive mt-5">
    <table id="blog-list" class="table table-striped blog-list">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Categories</th>
                <th>Tags</th>
                <th>Images</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody >
          @forelse ($blogs as $key=>$val)
              <tr>
                 <td>{{$key+1}}</td>
                 <td>{{$val->title}}</td>   
                  <td>{{$val->description}}</td>   
                 <td>{{$val->blog_category->category->name}}</td>   
                 <td>
                    {{$val->TagNames()}}
                 </td>
                 <td>
                    @if (count($val->blogImages))
                        <img src="{{$val->blogImages[0]->getImage()}}" style="max-height: 25px">
                    @endif    
                </td> 
                <td>
                  <div class="d-flex justify-content-between">
                      <div>
                          <a href="{{ route('admin.blogs.edit', ['blog' => $val->id]) }}" class="btn btn-info"> Edit </a>
                      </div>    
                      <div>
                          <button class="btn btn-danger delete-blog" data-blog-id="{{ $val->id }}"> Delete </button>
                      </div>    
                  </div>    
              </td>
            </tr>                    
          @empty
              <tr >
                <td colspan="10">
                    <div class="text-center p-4">
                        No Blogs Found
                    </div>
                </td>
              </tr>
          @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $blogs->links() }}
      </div>
</div>

@section('js')
    <!-- Include any additional scripts or links to JS files here -->
    <script>
        $(document).ready(function () {

                // Function to strip HTML tags
                function stripHtmlTags(html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                return doc.body.textContent || "";
            }


            // Delete Blog
            $('.delete-blog').on('click', function () {
                var blogId = $(this).data('blog-id');

                if (confirm("Are you sure you want to delete this blog?")) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.delete.blog') }}",
                        data: {
                            'blog_id': blogId,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            if (data.status) {
                                // Reload the page or update the blog list
                                window.location.reload();
                            } else {
                                alert('Failed to delete blog. Please try again.');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('An error occurred while processing your request. Please try again.');
                        }
                    });
                }
            });
            // Remove <p> tags from description
                $('td:nth-child(3)').each(function () {
                var content = $(this).html();
                var strippedContent = stripHtmlTags(content);
                $(this).html(strippedContent);
            });
        });
    </script>
@endsection
