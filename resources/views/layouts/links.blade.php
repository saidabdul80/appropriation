<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/images/e_portal.png">
	<link rel="stylesheet" href="/lib/bootstrap-icons/font/bootstrap-icons.css">
	<!-- <link rel="stylesheet" href="/lib/css/bootstrap.min.css"> -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
		<!-- <link rel="stylesheet" href="/lib/css/select2.css"> -->
		<link rel="stylesheet" href="/lib/css/swift-menu.css">
		<link rel="stylesheet" href="/lib/css/vivify.css">
		<link rel="stylesheet" href="/lib/js/datatable.css">
		<link rel="stylesheet" href="/lib/css/components.css">

		<link rel="stylesheet" href="/lib/css/font-awesome-4.7.0/css/font-awesome.min.css">
	<!-- <link href="/lib/js/listjs/PagingStyle.css" rel="stylesheet" /> -->
		<!-- <link rel="stylesheet" href="/lib/css/vue-tagin.css"> -->
	<script src="/vendor/jquery/jquery-3.2.1.min.js"></script>

	<link rel="stylesheet"  href="/lib/css/style3.css">

	<link rel="stylesheet" href="/lib/css/boostrap4.css">
	@if(request()->path() !== 'login')

	<script src="/lib/js/swift-menu.js"></script>
	<!-- <script src="/lib/js/jspdf.js"></script> -->
	<script src="/lib/js/chart.js"></script>
	<link rel="stylesheet"  href="/lib/css/sweetalert.css">
	<!-- <script src="/lib/js/listjs/paging.js"></script> -->

	<script src="/lib/js/datatable/datatables.min.js" defer></script>
	<script type="text/javascript" src="/lib/js/datatable/buttons.min.js"></script>
	<script type="text/javascript" src="/lib/js/datatable/jszip.min.js"></script>
	<script type="text/javascript" src="/lib/js/datatable/pdfmake.min.js"></script>
	<script type="text/javascript" src="/lib/js/datatable/vfs_fonts.js"></script>
	<!-- <script type="text/javascript" src="/lib/js/datatable/buttons.html5.min.js"></script>	 -->
	<script type="text/javascript" src="/lib/js/datatable/colvis.min.js"></script>
	<script type="text/javascript" src="/lib/js/datatable/col_reorder.js"></script>

<!-- 	<script type="text/javascript" src="/lib/js/select2.js"></script> -->
	<!-- <script src="/lib/js/jqueryvalidate.js"></script> -->
	<!-- <script src="/lib/js/propper.js"></script> -->
	<script src="/lib/js/sweetalert.js"></script>

	<script src="/lib/js/bootstrap4.js"></script>
<!-- 	<script src="/lib/js/vue.js"></script>
	<script type="module" src="/lib/js/vue-currency.js"></script>
	<script src="/lib/js/vue-tagins.js" ></script> -->

	<script src="/lib/js/axios.js"></script>
	<!-- <script src="/lib/js/datatable.css"></script> -->
	<link rel="stylesheet" href="/lib/css/styles.css">
	<!-- <script src="/lib/js/fullscreen.js"></script> -->
	@endif
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Smartresult') }}</title>

	<!-- Styles -->
	<style>
table.DTCR_clonedTable.dataTable {
  position: absolute !important;
  background-color: rgba(255, 255, 255, 0.7);
  z-index: 202;
}

div.DTCR_pointer {
  width: 1px;
  background-color: #0259C4;
  z-index: 201;
}
	</style>
	<script>
		$(document).ready(function() {
			var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
			var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
				return new bootstrap.Dropdown(dropdownToggleEl)
			})

			/* var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			}) */

			setTimeout(function() {
				switchPage()
			}, 600)
		})

		function showLoader() {
			$("#loaderHtml").show()
		}

		function hideLoader() {
			$("#loaderHtml").hide()
		}

		function switchPage(type = 1) {
			if (type == 1) {
				$("#appIn").css('visibility', 'visible');
				$("#loaderHtml").hide()
			} else {
				$("#loaderHtml").show()
				$("#appIn").css('visibility', 'hidden');
			}
		}

		function showAlert(msg, icon = 'success') {
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)

					//toast.addexpenseListener('mouseenter', Swal.stopTimer)
					//toast.addexpenseListener('mouseleave', Swal.resumeTimer)
				}
			})

			Toast.fire({
				icon: icon,
				title: msg
			})
		}
		var loaderHtml = ''

		async function postData(route, data, type = false, isFormData= false) {
			$("#loaderHtml").show()
			try{
				const config = isFormData ? { headers: { 'Content-Type': 'multipart/form-data' } } : {};
				return await axios.post(route, data, config).then(function(response) {
					//switchPage(1)
					$("#loaderHtml").hide()
					if (!type) { //if false
						showAlert(response.data);
					} else {
                        console.log(response,111122)
						return response;
					}
				}).catch(function(error) {
					$("#loaderHtml").hide()
					Swal.fire({
						text: error.response.data,
						icon: 'error',
					})
				})
			}catch(e){
				console.log(e)
			}
		}

		async function getData(route, loader =false) {
			if(loader){
				$("#loaderHtml").show()
			}

			try{
				return await axios.get(route).then(function(response) {
					//switchPage(1)
					$("#loaderHtml").hide()
					return response;
				}).catch(function(error) {
					//switchPage(1)
					$("#loaderHtml").hide()
					Swal.fire({
						text: error.response.data,
						icon: 'error',
					})
				})
			}catch(e){
				console.log(e)
			}
		}

		async function postDataWithoutLoader(route, data) {
			return await axios.post(route, data).then(function(response) {
				//switchPage(1)
					return response;
			}).catch(function(error) {
				//switchPage(1)
				$("#loaderHtml").hide()
				Swal.fire({
					text: error.response.data,
					icon: 'error',
				})
			})
		}

		function formatToCurrency(amount) {
			return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
		}

		function PrintElem(id, title) {

			var mywindow = window.open('', 'PRINT','left=0,top=0,toolbar=0,scrollbars=0,status=0');

			mywindow.document.write('<html><head><title>' +'<?= agencyName()?>' + '</title>');
			mywindow.document.write(`<link rel="stylesheet" href="{{asset('/lib/css/styles.css')}}"  >`);
			mywindow.document.write(`<link rel="stylesheet" href="{{asset('/lib/css/boostrap4.css')}} "  >`);
			mywindow.document.write(`</head><body>`);
			mywindow.document.write('<h1 class="text-center">' +'<?= agencyName()?>' + '</h1>');
			let html = $('#' + id).html();
			html = html.replace('height', '');
			html = html.replace('overflow', '');
			mywindow.document.write(html);
			mywindow.document.write('</body></html>');
			mywindow.document.close(); // necessary for IE >= 10
			mywindow.focus(); // necessary for IE >= 10*/

			setTimeout(() => {
				mywindow.print();
				mywindow.close();
			}, 2000);
			return true;
		}

		async function postDataWithAlert(url, data,elt ,wmsg='Are you sure you want to continue', smsg='success') {
				Swal.fire({
					text: wmsg,
					showCancelButton: true,
					showLoaderOnConfirm: true,
					preConfirm: async (value) => {
						if (value) {
							let res = await postData(url, data, true)
							return true;
						}
					},
					allowOutsideClick: () => !Swal.isLoading()
				}).then((result) => {
					if (result.isConfirmed) {
						showAlert(smsg);
						return true;
					}else{
						elt.checked = false;
					}

				})
			}
	</script>
</head>

<body class="background-primary px-2 w-100">
		@yield('body')

</body>
</html>
