@extends('admin.layouts.app')
@section('title', 'Edit Team')
@section('teams', 'active')
@section('content')
    <!-- Content Header (team header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Team</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.team.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" id="blogForm" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ $team->name }}"
                                        class="form-control" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        placeholder="Email" value="{{ $team->email }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile">Mobile<span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control"
                                        placeholder="Mobile" value="{{ $team->mobile }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="designation">Designation<span class="text-danger">*</span></label>
                                    <input type="text" name="designation" id="designation" class="form-control"
                                        placeholder="Designation" value="{{ $team->designation }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook_link">Facebook Link</label>
                                    <input type="text" name="social_link_1" id="social_link_1" class="form-control"
                                        placeholder="Facebook Link" value="{{ $team->social_link_1 }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="twitter_link">Twitter Link</label>
                                    <input type="text" name="social_link_2" id="social_link_2" class="form-control"
                                        placeholder="Twitter Link" value="{{ $team->social_link_2 }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="linkedin_link">LinkedIn Link</label>
                                    <input type="text" name="social_link_3" id="social_link_3" class="form-control"
                                        placeholder="LinkedIn Link" value="{{ $team->social_link_3 }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram_link">Instagram Link</label>
                                    <input type="text" name="social_link_4" id="social_link_4" class="form-control"
                                        placeholder="Instagram Link" value="{{ $team->social_link_4 }}">
                                    <p></p>
                                </div>
                            </div>
                            

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $team->status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $team->status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <input type="hidden" name="image_id" id="image_id">
                                <div class="mb-3">
                                    <label for="image">Image (294 x 313)</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($team->media_id))
                                    <div>
                                        <img width="200" src="{{ asset('uploads/teams/' . $team->media_id) }}"
                                            alt="">
                                    </div>
                                @endif

                            </div>

                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('admin.team.index') }}" class="btn btn-info">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        $('#blogForm').submit(function(e) {
            e.preventDefault();
            var elements = $(this);
            $('button[type=submit]').prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.team.update', $team->guid) }}",
                type: 'put',
                data: elements.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $('button[type=submit]').prop('disabled', false);
                    if (response['status'] == true) {

                        window.location.href = "{{ route('admin.team.index') }}";
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    } else {

                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('admin.team.index') }}";
                        }
                        var errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                        $.each(errors, function(key, val) {
                            $('#' + key).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(val);
                        });
                    }
                },
                error: function(jqXHR) {
                    console.log('Something went wrong.');
                }
            });
        });

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('admin.media.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection
