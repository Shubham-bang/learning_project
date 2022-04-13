@extends('admin.layouts.partials.footer')
@extends('admin.layouts.partials.content')
@extends('admin.layouts.partials.header')
@section ('title') Products Enquiry - Admin Dashboard @endsection 

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Products Enquiry</h1>
      <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Data</li>
        </ol>
      </nav> -->
      @if(session()->has('message'))
									<div class="alert alert-success alert-dismissible fade show" role="alert">
											<strong>Well done!</strong> {{ session()->get('message') }}
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								@endif
									@if(count($errors) > 0)
									@foreach($errors->all() as $error)
												<div class="alert alert-danger alert-dismissible fade show" role="alert">
											<strong>Oops...!!</strong>{{ $error }} 
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
											@endforeach
								@endif
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body pt-4">
              <!-- <h5 class="card-title">Datatables</h5> -->

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Merchent Name</th>
                    <th scope="col">Product Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($products_requests as $product)
                    <tr>
                      <th scope="row">{{ $loop->iteration }}</th>
                      <td>{{ $product->category->name }}</td>
                      <td>{{ $product->product_namee }}</td>
                      <td>{{ $product->merchent->name }}</td>
                      <td>{{ $product->product_description }}</td>
                      <td>
                         @if ($product->status == '1')
                          <button class="btn btn-success btn-sm">Active</button>
                         @elseif ($product->status == '0') 
                         <button class="btn btn-danger btn-sm">Inactive</button>
                         @endif
                      </td>
                      <td>
                         <!-- <a href="{{ route('admin.cate_edit',$product->id) }}" class="btn btn-primary btn-sm">Edit</a> -->
                      </td>
                    </tr>
                    @endforeach
                  
                  <!-- <tr>
                    <th scope="row">2</th>
                    <td>Bridie Kessler</td>
                    <td>Developer</td>
                    <td>35</td>
                    <td>2014-12-05</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>Ashleigh Langosh</td>
                    <td>Finance</td>
                    <td>45</td>
                    <td>2011-08-12</td>
                  </tr>
                  <tr>
                    <th scope="row">4</th>
                    <td>Angus Grady</td>
                    <td>HR</td>
                    <td>34</td>
                    <td>2012-06-11</td>
                  </tr>
                  <tr>
                    <th scope="row">5</th>
                    <td>Raheem Lehner</td>
                    <td>Dynamic Division Officer</td>
                    <td>47</td>
                    <td>2011-04-19</td>
                  </tr> -->
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->


@section('content')