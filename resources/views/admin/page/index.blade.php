@extends('admin.layouts.app')
@section('title', 'Pages')
@section('pages', 'active')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pages</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.page.create') }}" class="btn btn-primary">New Page</a>
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
                                <a href="{{ route('admin.page.index') }}" class="btn btn-danger btn-sm">Reset</a>

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
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($pages->isNotEmpty())

                                        @foreach ($pages as $page)
                                            <tr>
                                                <td>{{ $page->id }}</td>
                                                <td>{{ $page->name }}</td>
                                                <td>{{ $page->slug }}</td>
                                                <td>
                                                    @if ($page->is_active == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Block</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('admin.page.edit', $page->id) }}">
                                                        <i class="fas fa-pencil-alt">
                                                        </i>

                                                    </a>

                                                    <a href="javascript:void()" class="btn btn-danger btn-sm"
                                                        onclick="deletePage({{ $page->id }})">
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
                            {!! $pages->links('pagination::bootstrap-5') !!}
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
        function deletePage(id) {
            var url = "{{ route('admin.page.delete', 'ID') }}";
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
                            window.location.href = "{{ route('admin.page.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
