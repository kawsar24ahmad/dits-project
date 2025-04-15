@extends('admin.layouts.app')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('admin.services.index') }}">Services</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="basic-vertical-layouts">
                <form class="form form-vertical" action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add New Service</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Title -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                                                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>



                                        <!-- Description -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Description <span class="text-danger">*</span></label>
                                                <textarea id="summernote" class="form-control" name="description">{{ old('description') }}</textarea>
                                                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Price <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="price" value="{{ old('price') }}" step="0.01" required>
                                                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <!-- Offer Price -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Offer Price</label>
                                                <input type="number" class="form-control" name="offer_price" value="{{ old('offer_price') }}" step="0.01">
                                                @error('offer_price') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <!-- Category -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Category <span class="text-danger">*</span></label>
                                                <select class="form-control js-example-basic-single" name="category_id" required>
                                                    <option value="">-- Select Category --</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <!-- Thumbnail -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Thumbnail <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="thumbnail" required>
                                                @error('thumbnail') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>



                                        <!-- Is Active -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control" name="is_active">
                                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        $('#summernote').summernote({
            placeholder: 'Write description here...',
            tabsize: 2,
            height: 150,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection
