@dd($errors)
<form action="{{route('front.contact.send_message')}}" method="post" id="main_contact_form">
    @csrf <!--Cross Site Request Forgery zastita -->    
    <div class="contact_input_area">
        <div id="success_fail_info"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text"
                           value="{{old('your_name')}}" 
                           class="form-control @if($errors->has('your_name') is-invalid @endif" 
                           name="your_name" 
                           id="f-name" 
                           placeholder="{{__('Your Name')}}" 
                           required
                           >
                    @include('front._layout.partials.form_errors',['field_name'=>'your_name'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="email" 
                           value="{{old('your_email')}}" 
                           class="form-control @if($errors->has('your_email') is-invalid @endif" 
                           name="your_email" 
                           id="email" 
                           placeholder="@lang('Your E-mail')"
                           required
                           >
                    @include('front._layout.partials.form_errors',['field_name'=>'your_email'])
                </div>
            </div>
            <div class="col-12">
                <div class="form-group @if($errors->has('message') is-invalid @endif">
                    <textarea name="message" 
                              class="form-control" 
                              id="message" 
                              cols="30" 
                              rows="10" 
                              placeholder="@lang('Your Message')"
                              required
                              >{{old('message')}}</textarea>
                    @include('front._layout.partials.form_errors',['field_name'=>'message'])
                </div>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary w-100">Send Message</button>
            </div>
        </div>
    </div>
</form>


@push('footer_javascript')   
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"> </script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/localization/messages_sr_lat.js"></script>-->
<script type="text/javascript">
    $('#main_contact_form').validate({
        "rules" : {
            "your_name" : {
                "required" : true,
                "minlength" : 2
            },
            "your_email" : {
                "required" : true,
                "email" : true
            },
            "message" : {
                "required" : true,
                "minlength" : 50,
                "maxlength" : 255
            }
        },
        "errorPlacement" : function(error,element){
            error.addClass('text-danger');
            error.insertAfter(element);
            //element.addClass('is-invalid');
        }
    });
</script>
@endpush