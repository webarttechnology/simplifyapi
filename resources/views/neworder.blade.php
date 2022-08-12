<x-header-component /> 
 <section class="minbody">
     <div class="agentspg">
         <div class="pgmincontent pt-4">
             <div class="container">
                 <div class="row justify-content-end">
                     <div class="col-md-12">
                         <div class="cmpnycontentbox wow bounceInDown" data-wow-delay=".6s">
                             <h1 class="d-inline-block display-5 fw-bold mt-3 py-2 mb-3 ">Order Info</h1>
                            
                            <div id="errmsg" style="color:red; padding-left:5px ">{{ Session::get('errmsg') }}</div>
                            <div id="successmsg" style="color:green; padding-left:5px ">{{ Session::get('successmsg') }}</div>
                            <form class="form-horizontal"  action="{{ url('new-order') }}" method='POST' autocomplete="off" >
                            @csrf
                                 <div class="row">
                                 <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="exampleInputEmail1" class="form-label">Country Name : <small class="text-danger">*</small> </label>
                                             <select  class="form-select" id="county" name="county" onchange="showjurisdiction()">
                                                <option value="">Select A County</option>
                                                @foreach($country as $item)                                                               
                                                <option value="{{ $item->recipientID  }}">{{ $item->name.' ('.$item->state.' )'  }}</option>
                                                @endforeach
                                            </select>                                            
                                            @if ($errors->has('county'))
                                            <span class="text-danger">{{ $errors->first('county') }}</span>
                                             @endif
                                            </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="exampleInputEmail1" class="form-label">Document Type To Record: <small class="text-danger">*</small></label>
                                             <select  class="form-select" name="jurisdiction" id="jurisdiction" onchange="ordersummary()">
                                                <option selected value="">Select</option>
                                            </select>
                                            <span id="jurisdiction_error" class="text-danger"></span>
                                             @if ($errors->has('jurisdiction'))
                                            <span class="text-danger">{{ $errors->first('jurisdiction') }}</span>
                                             @endif
                                         </div>
                                     </div>
                                 </div>  
                                 <input type="submit" class="btn btn-primary" value="Submit">
                             </form>
                            
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         
     </div>
 </section>
 <x-footer-component />


<script>
    const showjurisdiction = () => {
    const countryCode = $("#county").val();
     $.ajax({
        type: 'get',
        url: 'get-jusisdiction',
        data: {countryCode: countryCode},
        success: function(data){
            $("#jurisdiction").html(data);
        }
    })
    $('#div_jurisdiction').show();
}
</script>