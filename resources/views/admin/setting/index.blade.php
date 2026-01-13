@extends('layouts.admin.master')
@section('title', 'Settings')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Settings </h4>
                            </div>
                            <form id="settingsForm" action="{{ route('setting.update') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="app_name" class="form-label">App Name</label>
                                            <input type="text" name="app_name" class="form-control" id="app_name"
                                                placeholder="Enter here" value="{{ old('app_name', @$setting->app_name) }}">
                                            @error('app_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="app_slogan" class="form-label">App Slogan</label>
                                            <input type="text" name="app_slogan" class="form-control" id="app_slogan"
                                                placeholder="Enter here" value="{{ old('app_slogan', @$setting->app_slogan) }}">
                                            @error('app_slogan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="point_per_coupon" class="form-label">Point Per Reference</label>
                                            <input type="number" name="point_per_coupon" class="form-control" id="point_per_coupon"
                                                placeholder="Enter here" value="{{ old('point_per_coupon', @$setting->point_per_coupon) }}">
                                            @error('point_per_coupon')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="point_per_registration" class="form-label">Point Per Registration</label>
                                            <input type="number" name="point_per_registration" class="form-control" id="point_per_registration"
                                                placeholder="Enter here" value="{{ old('point_per_registration', @$setting->point_per_registration) }}">
                                            @error('point_per_registration')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="is_point_by_registration" class="form-label">Is Point By Registration</label>
                                                <select name="is_point_by_registration" class="form-control" id="is_point_by_registration">
                                                    <option value="1" @if(@$setting->is_point_by_registration == 1)
                                                        selected @endif>Yes</option>
                                                    <option value="0" @if(@$setting->is_point_by_registration == 0)
                                                        selected @endif>No</option>
                                                </select>
                                            @error('is_point_by_registration')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="app_logo" class="form-label">App Logo</label>
                                            <input type="file" name="app_logo" id="app_logo" class="form-control" accept="image/*">
                                            @error('app_logo')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            <div class="mt-2">
                                                <img id="app-logo-preview"
                                                    src="{{ @$setting->app_logo ? Storage::url(@$setting->app_logo) : asset('defaults/noimage/no_img.jpg') }}"
                                                    alt="Image" style="max-width: 200px; max-height: 200px; border-radius: 8px;" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="app_background_image" class="form-label">App Background Image</label>
                                            <input type="file" name="app_background_image" id="app_background_image" class="form-control" accept="image/*">
                                            @error('app_background_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            <div class="mt-2">
                                                <img id="app-background-image-preview"
                                                    src="{{ @$setting->app_background_image ? Storage::url(@$setting->app_background_image) : asset('defaults/noimage/no_img.jpg') }}"
                                                    alt="Image" style="max-width: 200px; max-height: 200px; border-radius: 8px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Update
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('js')
    <script>
        $(document).ready(function() {
            let htmlForm = $('#settingsForm');
            let validationTimer;

            $.validator.addMethod("filesize", function(value, element, param) {
                if (element.files.length > 0) {
                    return element.files[0].size <= param;
                }
                return true;
            }, function(param, element) {
                let maxSizeInMB = (param / 1024 / 1024);
                return "File size must be less than " + maxSizeInMB + " MB";
            });

            let rules = {
                title: {
                    required: true,
                },
                app_logo: {
                    required: false,
                    accept: "image/*",
                    filesize: 5242880 // 5MB
                },
                app_background_image: {
                    required: false,
                    accept: "image/*",
                    filesize: 5242880 // 5MB
                },
            };


            htmlForm.validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                onkeyup: function(element) {
                    let validator = this;
                    clearTimeout(validationTimer);
                    validationTimer = setTimeout(function() {
                        validator.element(element);
                    }, 1000);
                },
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: rules,
                submitHandler: function(htmlForm) {
                    let button = $(htmlForm).find('button[type="submit"]:focus');
                    button.attr("disabled", true).css("cursor", "default");
                    button.html(
                        '<span class="submitting"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</span>'
                    );
                    htmlForm.submit();
                }
            });

            $.each(rules, function(key, item) {
                if (typeof item.required == "function" ? item.required() : item.required) {
                    $('label[for="' + key + '"]').first().append(
                        '<span style="vertical-align: text-top; font-family: Verdana,sans-serif;" class="text-danger">&nbsp;*</span>'
                    );
                }
            });


            $('#app_logo').change(function() {
                readURL(this, 'app-logo-preview');
            });

            $('#app_background_image').change(function() {
                readURL(this, 'app-background-image-preview');
            });


        });


        function readURL(input, previewElementId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + previewElementId).attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
