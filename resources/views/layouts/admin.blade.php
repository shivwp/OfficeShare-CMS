<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
  <title>{{ trans('panel.site_title') }}</title>
  {{-- <link rel="stylesheet" type="text/css" href="http://docs.ckeditor.com/#!/guide/dev_howtos_file_upload"> --}}
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />

  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/data-tables.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/stylem.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/intlTelInput.css')}}">
  <link href="{{ asset('css/css-circular-prog-bar.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/simple-donut.css')}}" type="text/css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap');
  </style>
  <style type="text/css">
    .select2-selection--multiple {
      width: 100% !important;
    }

    .nav-link.active {
      color: #fff!important;
      background-color: #fc6565 !important;
    }

    button.dt-button,
    div.dt-button,
    a.dt-button {
      background-image: linear-gradient(to bottom, #FF5722 0%, #FF5722 100%) !important;
      color: #fff !important;
    }

    .couponTab>ul>li {
      width: 33.3%;
      background: #32D3A1;
      text-align: center;
      height: 35px;
      line-height: 35px;
      border-right: 2px #fff solid;
      font-size: 15px;
    }

    .couponTab>ul>li>a {
      color: #fff !important;
    }
  </style>
  @stack('style-content')
  @yield('styles')
  <script id="mcjs">
    ! function(c, h, i, m, p) {
      m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)
    }(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/5ebcad3d798d0558a224752d6/f654dced7aac355171b307d72.js");
  </script>

<style type="text/css">
.overlay {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  right: 0;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0, 0.9);
  overflow-x: hidden;
  transition: 0.5s;
}

.overlay-content {
  position: relative;
  top: 25%;
  width: 100%;
  text-align: center;
  margin-top: 30px;
}

.overlay a {
  padding: 8px;
  text-decoration: none;
  font-size: 36px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
  color: #f1f1f1;
}

 .closebtn {
      font-size: 60px;
    float: right;
  
}

@media screen and (max-height: 450px) {
  .overlay a {font-size: 20px}
  .overlay .closebtn {
  font-size: 40px;
  top: 15px;
  right: 35px;
  }
}
 article, aside, figure, footer, header, hgroup, 
  menu, nav, section { display: block; }
</style>


</head>

<body style="height: auto;">
  <div class="wrapper">
    @include('partials.menu')
    <div class="main-panel">

<!-- 
<div  class="overlay">
  
  <div class="overlay-content">
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Clients</a>
    <a href="#">Contact</a>
  </div>
</div> -->
<div class="btn_grp">
<span class="nav-burger"  onclick="openNav()">&#9776; </span>
<span href="javascript:void(0)" class="closebtn" onclick="closeNav()" style="display: none;">&times;</span>
 </div>


<!-- <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; </span>
 -->
      <!-- Main content -->
      <section class="content" style="padding-top: 0px">
        @if(session('message'))
        <div class="row mb-2">
          <div class="col-lg-12">
            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
          </div>
        </div>
        @endif
        @if($errors->count() > 0)
        <div class="alert alert-danger">
          <ul class="list-unstyled">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @yield('content')
      </section>
      <!-- /.content -->
      <footer class="footer justify-content-center">
        <strong> &copy;</strong> {{ trans('global.allRightsReserved') }}
      </footer>
    </div>
    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
  </div>
  
 <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
 {{-- <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script> --}}
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.2.0/dist/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

  <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
  <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/bootstrap-material-design.min.js') }}"></script>
  <script src="{{ asset('js/material-dashboard.js') }}"></script>
    <script src="{{ asset('js/data-tables.js') }}"></script>
      <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
          <script src="{{ asset('js/vendors.min.js') }}"></script>
           <script src="{{ asset('js/select2.full.min.js') }}"></script>
           <script src="{{ asset('js/jquery.geocomplete.js') }}"></script>
           <script src="{{ asset('js/intlTelInput.min.js')}}"></script>
           <script src="{{ asset('js/intlTelInput-jquery.min.js')}}"></script>
           <script src="{{ asset('js/utils.js')}}"></script>
           <script src="{{ asset('js/chart.min.js')}}"></script>
        

  <script>CKEDITOR.replace( 'editor1' );</script>
  
  <script>
    function ckeditor($name,$value='',$height=300){
      return '<textarea name="'+addslashes($name)+'">'+htmlspecialchars($value)+'</textarea>';
    }
  </script> 
<script>
$(function(){CKEDITOR.replace("'.addslashes($name).'",{allowedContent: true});});
</script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          'pdfHtml5'
        ]
      });
    });
    $(document).ready(function() {
      $(document).on('click', '.reload', function(event) {
        location.reload();
      });
    });
  </script>
  
  
  <script>
    $(function() {
      let copyButtonTrans = '{{ trans('global.datatables.copy ') }}'
      let csvButtonTrans = '{{ trans('global.datatables.csv ') }}'
      let excelButtonTrans = '{{ trans('global.datatables.excel ') }}'
      let pdfButtonTrans = '{{ trans('global.datatables.pdf ') }}'
      let printButtonTrans = '{{ trans('global.datatables.print ') }}'
      let colvisButtonTrans = '{{ trans('global.datatables.colvis ') }}'

      let languages = {
        'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
      };

      $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
        className: 'btn'
      })
      $.extend(true, $.fn.dataTable.defaults, {
        language: {
          url: languages['{{ app()->getLocale() }}']
        },
        columnDefs: [{
          orderable: false,
          className: 'select-checkbox',
          targets: 0
        }, {
          orderable: false,
          searchable: false,
          targets: -1
        }],
        select: {
          style: 'multi+shift',
          selector: 'td:first-child'
        },
        order: [],
        scrollX: true,
        pageLength: 100,
        dom: 'lBfrtip<"actions">',
        buttons: [{
            extend: 'copy',
            className: 'btn-default',
            text: copyButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            extend: 'csv',
            className: 'btn-default',
            text: csvButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            extend: 'excel',
            className: 'btn-default',
            text: excelButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            extend: 'pdf',
            className: 'btn-default',
            text: pdfButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            extend: 'print',
            className: 'btn-default',
            text: printButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            extend: 'colvis',
            className: 'btn-default',
            text: colvisButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          }
        ]
      });

      $.fn.dataTable.ext.classes.sPageButton = '';
    });
  </script>
  <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.6.2/tinymce.min.js" integrity="sha512-sOO7yng64iQzv/uLE8sCEhca7yet+D6vPGDEdXCqit1elBUAJD1jYIYqz0ov9HMd/k30e4UVFAovmSG92E995A==" crossorigin="anonymous"></script>
  <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(".select2-icons").select2({
       dropdownAutoWidth: true,
       width: '100%',
       minimumResultsForSearch: Infinity,
       templateResult: iconFormat,
       templateSelection: iconFormat,
       escapeMarkup: function (es) { return es; }
   });
   
   // Format icon 
   function iconFormat(icon) {
       var originalOption = icon.element;
       if (!icon.id) { return icon.text; }
       var $icon = "" + $(icon.element).data('icon') + "" + icon.text;
   
       return $icon;
   }
   
       
   // Limiting the number of selections
   $(".max-length").select2({
       dropdownAutoWidth: true,
       width: '100%',
      
   });
</script>
  <script>
        tinymce.init({
           selector: '.editor1',

            image_class_list: [
            {title: 'img-responsive', value: 'img-responsive'},
            ],
            height: 500,
            setup: function (editor) {
                editor.on('init change', function () {
                    editor.save();
                });
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste imagetools"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",

            image_title: true,
            automatic_uploads: true,
            images_upload_url: '/dashboard/upload',
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                };
                input.click();
            }
        });
</script>

  <script>
    ! function(e, t) {
      "object" == typeof exports && "undefined" != typeof module ? t(exports) : "function" == typeof define && define.amd ? define(["exports"], t) : t(e.adminlte = {})
    }(this, function(e) {
      "use strict";
      var i, t, o, n, r, a, s, c, f, l, u, d, h, p, _, g, y, m, v, C, D, E, A, O, w, b, L, S, j, T, I, Q, R, P, x, B, M, k, H, N, Y, U, V, G, W, X, z, F, q, J, K, Z, $, ee, te, ne = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
          return typeof e
        } : function(e) {
          return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        },
        ie = function(e, t) {
          if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
        },
        oe = (i = jQuery, t = "ControlSidebar", o = "lte.control.sidebar", n = i.fn[t], r = ".control-sidebar", a = '[data-widget="control-sidebar"]', s = ".main-header", c = "control-sidebar-open", f = "control-sidebar-slide-open", l = {
          slide: !0
        }, u = function() {
          function n(e, t) {
            ie(this, n), this._element = e, this._config = this._getConfig(t)
          }
          return n.prototype.show = function() {
            this._config.slide ? i("body").removeClass(f) : i("body").removeClass(c)
          }, n.prototype.collapse = function() {
            this._config.slide ? i("body").addClass(f) : i("body").addClass(c)
          }, n.prototype.toggle = function() {
            this._setMargin(), i("body").hasClass(c) || i("body").hasClass(f) ? this.show() : this.collapse()
          }, n.prototype._getConfig = function(e) {
            return i.extend({}, l, e)
          }, n.prototype._setMargin = function() {
            i(r).css({
              top: i(s).outerHeight()
            })
          }, n._jQueryInterface = function(t) {
            return this.each(function() {
              var e = i(this).data(o);
              if (e || (e = new n(this, i(this).data()), i(this).data(o, e)), "undefined" === e[t]) throw new Error(t + " is not a function");
              e[t]()
            })
          }, n
        }(), i(document).on("click", a, function(e) {
          e.preventDefault(), u._jQueryInterface.call(i(this), "toggle")
        }), i.fn[t] = u._jQueryInterface, i.fn[t].Constructor = u, i.fn[t].noConflict = function() {
          return i.fn[t] = n, u._jQueryInterface
        }, u),
        re = (d = jQuery, h = "Layout", p = "lte.layout", _ = d.fn[h], g = ".main-sidebar", y = ".main-header", m = ".content-wrapper", v = ".main-footer", C = "hold-transition", D = function() {
          function n(e) {
            ie(this, n), this._element = e, this._init()
          }
          return n.prototype.fixLayoutHeight = function() {
            var e = {
                window: d(window).height(),
                header: d(y).outerHeight(),
                footer: d(v).outerHeight(),
                sidebar: d(g).height()
              },
              t = this._max(e);
            d(m).css("min-height", t - e.header), d(g).css("min-height", t - e.header)
          }, n.prototype._init = function() {
            var e = this;
            d("body").removeClass(C), this.fixLayoutHeight(), d(g).on("collapsed.lte.treeview expanded.lte.treeview collapsed.lte.pushmenu expanded.lte.pushmenu", function() {
              e.fixLayoutHeight()
            }), d(window).resize(function() {
              e.fixLayoutHeight()
            }), d("body, html").css("height", "auto")
          }, n.prototype._max = function(t) {
            var n = 0;
            return Object.keys(t).forEach(function(e) {
              t[e] > n && (n = t[e])
            }), n
          }, n._jQueryInterface = function(t) {
            return this.each(function() {
              var e = d(this).data(p);
              e || (e = new n(this), d(this).data(p, e)), t && e[t]()
            })
          }, n
        }(), d(window).on("load", function() {
          D._jQueryInterface.call(d("body"))
        }), d.fn[h] = D._jQueryInterface, d.fn[h].Constructor = D, d.fn[h].noConflict = function() {
          return d.fn[h] = _, D._jQueryInterface
        }, D),
        ae = (E = jQuery, A = "PushMenu", w = "." + (O = "lte.pushmenu"), b = E.fn[A], L = {
          COLLAPSED: "collapsed" + w,
          SHOWN: "shown" + w
        }, S = {
          screenCollapseSize: 768
        }, j = {
          TOGGLE_BUTTON: '[data-widget="pushmenu"]',
          SIDEBAR_MINI: ".sidebar-mini",
          SIDEBAR_COLLAPSED: ".sidebar-collapse",
          BODY: "body",
          OVERLAY: "#sidebar-overlay",
          WRAPPER: ".wrapper"
        }, T = "sidebar-collapse", I = "sidebar-open", Q = function() {
          function n(e, t) {
            ie(this, n), this._element = e, this._options = E.extend({}, S, t), E(j.OVERLAY).length || this._addOverlay()
          }
          return n.prototype.show = function() {
            E(j.BODY).addClass(I).removeClass(T);
            var e = E.Event(L.SHOWN);
            E(this._element).trigger(e)
          }, n.prototype.collapse = function() {
            E(j.BODY).removeClass(I).addClass(T);
            var e = E.Event(L.COLLAPSED);
            E(this._element).trigger(e)
          }, n.prototype.toggle = function() {
            (E(window).width() >= this._options.screenCollapseSize ? !E(j.BODY).hasClass(T) : E(j.BODY).hasClass(I)) ? this.collapse(): this.show()
          }, n.prototype._addOverlay = function() {
            var e = this,
              t = E("<div />", {
                id: "sidebar-overlay"
              });
            t.on("click", function() {
              e.collapse()
            }), E(j.WRAPPER).append(t)
          }, n._jQueryInterface = function(t) {
            return this.each(function() {
              var e = E(this).data(O);
              e || (e = new n(this), E(this).data(O, e)), t && e[t]()
            })
          }, n
        }(), E(document).on("click", j.TOGGLE_BUTTON, function(e) {
          e.preventDefault();
          var t = e.currentTarget;
          "pushmenu" !== E(t).data("widget") && (t = E(t).closest(j.TOGGLE_BUTTON)), Q._jQueryInterface.call(E(t), "toggle")
        }), E.fn[A] = Q._jQueryInterface, E.fn[A].Constructor = Q, E.fn[A].noConflict = function() {
          return E.fn[A] = b, Q._jQueryInterface
        }, Q),
        se = (R = jQuery, P = "Treeview", B = "." + (x = "lte.treeview"), M = R.fn[P], k = {
          SELECTED: "selected" + B,
          EXPANDED: "expanded" + B,
          COLLAPSED: "collapsed" + B,
          LOAD_DATA_API: "load" + B
        }, H = ".nav-item", N = ".nav-treeview", Y = ".menu-open", V = "menu-open", G = {
          trigger: (U = '[data-widget="treeview"]') + " " + ".nav-link",
          animationSpeed: 300,
          accordion: !0
        }, W = function() {
          function i(e, t) {
            ie(this, i), this._config = t, this._element = e
          }
          return i.prototype.init = function() {
            this._setupListeners()
          }, i.prototype.expand = function(e, t) {
            var n = this,
              i = R.Event(k.EXPANDED);
            if (this._config.accordion) {
              var o = t.siblings(Y).first(),
                r = o.find(N).first();
              this.collapse(r, o)
            }
            e.slideDown(this._config.animationSpeed, function() {
              t.addClass(V), R(n._element).trigger(i)
            })
          }, i.prototype.collapse = function(e, t) {
            var n = this,
              i = R.Event(k.COLLAPSED);
            e.slideUp(this._config.animationSpeed, function() {
              t.removeClass(V), R(n._element).trigger(i), e.find(Y + " > " + N).slideUp(), e.find(Y).removeClass(V)
            })
          }, i.prototype.toggle = function(e) {
            var t = R(e.currentTarget),
              n = t.next();
            if (n.is(N)) {
              e.preventDefault();
              var i = t.parents(H).first();
              i.hasClass(V) ? this.collapse(R(n), i) : this.expand(R(n), i)
            }
          }, i.prototype._setupListeners = function() {
            var t = this;
            R(document).on("click", this._config.trigger, function(e) {
              t.toggle(e)
            })
          }, i._jQueryInterface = function(n) {
            return this.each(function() {
              var e = R(this).data(x),
                t = R.extend({}, G, R(this).data());
              e || (e = new i(R(this), t), R(this).data(x, e)), "init" === n && e[n]()
            })
          }, i
        }(), R(window).on(k.LOAD_DATA_API, function() {
          R(U).each(function() {
            W._jQueryInterface.call(R(this), "init")
          })
        }), R.fn[P] = W._jQueryInterface, R.fn[P].Constructor = W, R.fn[P].noConflict = function() {
          return R.fn[P] = M, W._jQueryInterface
        }, W),
        ce = (X = jQuery, z = "Widget", q = "." + (F = "lte.widget"), J = X.fn[z], K = {
          EXPANDED: "expanded" + q,
          COLLAPSED: "collapsed" + q,
          REMOVED: "removed" + q
        }, $ = "collapsed-card", ee = {
          animationSpeed: "normal",
          collapseTrigger: (Z = {
            DATA_REMOVE: '[data-widget="remove"]',
            DATA_COLLAPSE: '[data-widget="collapse"]',
            CARD: ".card",
            CARD_HEADER: ".card-header",
            CARD_BODY: ".card-body",
            CARD_FOOTER: ".card-footer",
            COLLAPSED: ".collapsed-card"
          }).DATA_COLLAPSE,
          removeTrigger: Z.DATA_REMOVE
        }, te = function() {
          function n(e, t) {
            ie(this, n), this._element = e, this._parent = e.parents(Z.CARD).first(), this._settings = X.extend({}, ee, t)
          }
          return n.prototype.collapse = function() {
            var e = this;
            this._parent.children(Z.CARD_BODY + ", " + Z.CARD_FOOTER).slideUp(this._settings.animationSpeed, function() {
              e._parent.addClass($)
            });
            var t = X.Event(K.COLLAPSED);
            this._element.trigger(t, this._parent)
          }, n.prototype.expand = function() {
            var e = this;
            this._parent.children(Z.CARD_BODY + ", " + Z.CARD_FOOTER).slideDown(this._settings.animationSpeed, function() {
              e._parent.removeClass($)
            });
            var t = X.Event(K.EXPANDED);
            this._element.trigger(t, this._parent)
          }, n.prototype.remove = function() {
            this._parent.slideUp();
            var e = X.Event(K.REMOVED);
            this._element.trigger(e, this._parent)
          }, n.prototype.toggle = function() {
            this._parent.hasClass($) ? this.expand() : this.collapse()
          }, n.prototype._init = function(e) {
            var t = this;
            this._parent = e, X(this).find(this._settings.collapseTrigger).click(function() {
              t.toggle()
            }), X(this).find(this._settings.removeTrigger).click(function() {
              t.remove()
            })
          }, n._jQueryInterface = function(t) {
            return this.each(function() {
              var e = X(this).data(F);
              e || (e = new n(X(this), e), X(this).data(F, "string" == typeof t ? e : t)), "string" == typeof t && t.match(/remove|toggle/) ? e[t]() : "object" === ("undefined" == typeof t ? "undefined" : ne(t)) && e._init(X(this))
            })
          }, n
        }(), X(document).on("click", Z.DATA_COLLAPSE, function(e) {
          e && e.preventDefault(), te._jQueryInterface.call(X(this), "toggle")
        }), X(document).on("click", Z.DATA_REMOVE, function(e) {
          e && e.preventDefault(), te._jQueryInterface.call(X(this), "remove")
        }), X.fn[z] = te._jQueryInterface, X.fn[z].Constructor = te, X.fn[z].noConflict = function() {
          return X.fn[z] = J, te._jQueryInterface
        }, te);
      e.ControlSidebar = oe, e.Layout = re, e.PushMenu = ae, e.Treeview = se, e.Widget = ce, Object.defineProperty(e, "__esModule", {
        value: !0
      })
    });
    // # sourceMappingURL=adminlte.min.js.map
   {{--  $( window ).resize(function() {
  
      if($(this).width() <= 992){
      
          jQuery (".sidebar, .off-canvas-sidebar nav .navbar-collapse").css("display", "none");

        // $( "body" ).append( "<table id='dynamicTable'><tr><td>Table created</td></tr></table>");

      }else if($(this).width() > 992){
      
        jQuery (".sidebar, .off-canvas-sidebar nav .navbar-collapse").css("display", "block");

        
        // $( "#dynamicTable" ).remove();
      }

    });--}}
 
    CKEDITOR.replace('editor11', {
    // 
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
      filebrowserUploadMethod: 'form', 
      height: '300px',
    }).config.allowedContent = true;
    config.extraPlugins = 'image';
    config.extraPlugins = 'video';

</script>
  @yield('scripts')
  @stack('ajax-script')

</body>

</html>