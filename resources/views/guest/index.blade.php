<!doctype html>
<html lang="en">
  <head>
  	<title>Guest Book</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="/assets/style.css">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
		      	<div class="icon d-flex align-items-center justify-content-center">
		      		<span class="fa fa-user-o"></span>
		      	</div>
		      	<h3 class="text-center mb-4">Create Book Guest</h3>
                  <form method="POST" action="{{ route('store') }}" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama <sup style='color: red'>*</sup></label>
                        <input type="text" name="name" class="form-control rounded-left @error('name') is-invalid @enderror" placeholder="Masukan Nama">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon <sup style='color: red'>*</sup></label>
                        <input type="number" name="phone" class="form-control rounded-left @error('phone') is-invalid @enderror" placeholder="Masukan Nomor Telepon">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="keperluan">Keperluan <sup style='color: red'>*</sup></label>
                        <input type="text" name="purpose" class="form-control rounded-left @error('purpose') is-invalid @enderror" placeholder="Masukan Kerpeluan">
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-primary rounded submit px-3">Submit</button>
                    </div>
                </form>
            <div class="member" style="text-align: right;">
                <a href="/admin">Login</a>
            </div>
	        </div>
				</div>
			</div>
		</div>
	</section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (Session::has('success'))
            var msg = "{{ session('success') }}";
            Swal.fire(
                'Success!',
                msg,
                'success'
            ).then(function() {
                window.location.href = "{{ route('room.index') }}"
            })
          @endif
          @if (Session::has('error'))
            var msg = "{{ session('error') }}";
            Swal.fire(
                'Error!',
                msg,
                'error'
            )
          @endif
      </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

	</body>
</html>



