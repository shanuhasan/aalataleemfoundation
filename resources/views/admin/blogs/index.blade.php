@extends('admin.layouts.app')
@section('title', 'Blogs')
@section('blogs', 'active')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blogs</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">New blog</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="" method="get">
                            <div class="card-header">
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-danger btn-sm">Reset</a>

                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" value="{{ Request::get('keyword') }}" name="keyword"
                                            class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($blogs->isNotEmpty())
                                        <?php $i = 1; ?>
                                        @foreach ($blogs as $blog)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $blog->title }}</td>
                                                <td>{{ $blog->slug }}</td>
                                                <td>
                                                    @if ($blog->status == 1)
                                                        <span class="badge badge-success">Publish</span>
                                                    @else
                                                        <span class="badge badge-danger">Draft</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('admin.blog.edit', $blog->id) }}">
                                                        <i class="fas fa-pencil-alt">
                                                        </i>

                                                    </a>

                                                    <a href="javascript:void()" class="btn btn-danger btn-sm"
                                                        onclick="deleteBlog({{ $blog->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">Record Not Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {!! $blogs->links('pagination::bootstrap-5') !!}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('script')
    <script>
        function deleteBlog(id) {
            var url = "{{ route('admin.blog.delete', 'ID') }}";
            var newUrl = url.replace('ID', id);

            if (confirm('Are you sure want to delete')) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['status']) {
                            window.location.href = "{{ route('admin.blog.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
