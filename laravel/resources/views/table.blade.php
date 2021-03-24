<!DOCTYPE html>
<html lang="en">
<head>
	<title>Table V02</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	 <!-- Latest compiled and minified CSS 
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->

	<!-- jQuery library 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->

	<!-- Latest compiled JavaScript 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
	<script src="config.js" type="text/javascript"></script>
	
	<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{url('')}}/assets/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="{{url('')}}/assets/css/main.css">
	<script src="{{url('')}}/assets/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="{{url('')}}/assets/vendor/bootstrap/js/popper.js"></script>
	<script src="{{url('')}}/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="{{url('')}}/assets/vendor/select2/select2.min.js"></script>
	<script src="{{url('')}}/assets/js/main.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script type="text/javascript">
        var mqtt;
        var reconnectTimeout = 2000;
        var data_berat = [];
        var chart;
        var xhr;
        
		function deleteAll(){
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire(
						'Deleted!',
						'Your data has been deleted.',
						'success'
					);
					location.href = "{{route('deleteAll')}}";

				}
			})
		};

    </script>
	
</head>
<body>
	
	<div class="limiter">
		<div class="container-table100">
		<button onClick="deleteAll()" type="button" class="btn btn-danger" style="margin-left:100px;">Reset</button>
			<div class="wrap-table100"style="margin-left:10px;" >
				{{$berat->links()}} 
			</div>
			<div class="wrap-table100">
				<div class="table">
					<div class="row header">
						<div class="cell">No</div>
						<div class="cell">Berat (g)</div>
						<div class="cell">Waktu</div>
					</div>
						@forelse ($berat as $data)
							<div class="row">
								<div class="cell" data-title="Full Name">
									{{$jumlah - $data->id + 1}}
								</div>
								<div class="cell" data-title="Full Name">
									{{$data->berat}}
								</div>
								<div class="cell" data-title="Age">
									{{\Carbon\Carbon::parse($data->created_at)->format('l, d-m-Y h:m:s') }}
								</div>
							</div>
						@empty
                    		<td colspan="6" class="text-center">Tidak ada data...</td>
                		@endforelse
					</div>
			</div>
		</div>
	</div>
</body>
</html>