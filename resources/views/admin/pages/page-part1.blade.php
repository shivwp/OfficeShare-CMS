 <div class="form-group {{$errors->has('content')?'has-error' : '' }}">
                <label for="email">Middle Contents*</label>
                <textarea style="height:300px !important;" class="editor2 form-control" id="editor12" name="content[middle][]">{{isset($page->sections->middle[0])?$page->sections->middle[0]:'' }}</textarea>
            </div>

            @push('ajax-script')

            <script>
                  CKEDITOR.replace('editor12', {
                    filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                    filebrowserUploadMethod: 'form', 
                    height: '300px',
                    }).config.allowedContent = true;
                     config.extraPlugins = 'image';
                    config.extraPlugins = 'video';
            </script>

            @endpush