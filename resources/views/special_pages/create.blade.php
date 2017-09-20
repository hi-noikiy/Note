@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-md-9">

            <div class="panel panel-default padding-md">

                <div class="panel-body ">

                    <h3 class="text-center">
                        添加页面
                    </h3>
                    <hr>

                    <page-md-editor></page-md-editor>

                </div>

            </div>
        </div>


        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">提示信息</div>

                <div class="panel-body">
                    这是奥克兰发还发爱古法卡法咖啡馆花费
                </div>
            </div>
        </div>


    </div>

@endsection