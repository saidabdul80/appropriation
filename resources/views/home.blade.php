@extends('layouts/master')
@section('content')

<div class="background px-4 py-2" style="height: 92.1vh;">
    <div>
    <script>
    const {
        createApp
    } = Vue
    createApp({
        data() {
            return {
               
            }
        },
        computed: {

        },
        methods: {
          
        },
        mounted() {


        }

    }).mount('#appIn')
    $(document).ready(function() {
        
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $('#toggleCards').click(function() {
            $(this).find('i').toggleClass('bi-chevron-up')
            $('.cards-container').slideToggle();
        })



    })
</script>
@endsection