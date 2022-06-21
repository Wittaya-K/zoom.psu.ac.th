@extends('layouts.app')

@section('content')
<style>
  .fc-time
  {
   display : none;
  }
  .back-link a {
      color: #4ca340;
      text-decoration: none;
      border-bottom: 1px #4ca340 solid;
  }

  .back-link a:hover,
  .back-link a:focus {
      color: #408536;
      text-decoration: none;
      border-bottom: 1px #408536 solid;
  }

  h1 {
      height: 100%;
      /* The html and body elements cannot have any padding or margin. */
      margin: 0;
      font-size: 14px;
      font-family: 'Open Sans', sans-serif;
      font-size: 32px;
      margin-bottom: 3px;
  }

  .entry-header {
      text-align: left;
      margin: 0 auto 50px auto;
      width: 80%;
      max-width: 978px;
      position: relative;
      z-index: 10001;
  }

  #demo-content {
      /* padding-top: 100px; */
  }
  .fc-sat { color:blue; }
  .fc-sun { color:red;  }
</style>

	<!-- Demo content -->			
	<div id="demo-content">

		<div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

		</div>

    <div class="row">
      <div class="col-md-10">
          <div class="panel panel-default">
              <div class="panel-heading">ปฏิทินการจอง</div>

              <div class="panel-body">
                  {{-- @lang('quickadmin.qa_dashboard_text') --}}
                  <div class="col-md-12">
                      <div id="calendar" class="fc fc-unthemed fc-ltr">
                          <div class="fc-toolbar fc-header-toolbar">
                              <div class="fc-left">
                                  <div class="fc-button-group"><button type="button"
                                          class="fc-prev-button fc-button fc-state-default fc-corner-left"
                                          aria-label="prev"><span
                                              class="fc-icon fc-icon-left-single-arrow"></span></button><button type="button"
                                          class="fc-next-button fc-button fc-state-default fc-corner-right"
                                          aria-label="next"><span class="fc-icon fc-icon-right-single-arrow"></span></button>
                                  </div><button type="button"
                                      class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled"
                                      disabled="">today</button>
                              </div>
                              <div class="fc-right">
                                  <div class="fc-button-group"><button type="button"
                                          class="fc-month-button fc-button fc-state-default fc-corner-left fc-state-active">month</button><button
                                          type="button"
                                          class="fc-agendaWeek-button fc-button fc-state-default">week</button><button
                                          type="button"
                                          class="fc-agendaDay-button fc-button fc-state-default fc-corner-right">day</button>
                                  </div>
                              </div>
                              <div class="fc-clear"></div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

	</div>

@stop
@section('javascript')
    @parent
    <script>

        $(function () {
      
      /* initialize the external events
       -----------------------------------------------------------------*/
      function init_events(ele) {
        ele.each(function () {
      
          // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
          // it doesn't need to have a start or end
          var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
          }
      
          // store the Event Object in the DOM element so we can get to it later
          $(this).data('eventObject', eventObject)
      
          // make the event draggable using jQuery UI
          $(this).draggable({
            zIndex        : 1070,
            revert        : true, // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
          })
      
        })
      }
      function formatDate(date) {
      var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();

      if (month.length < 2) 
          month = '0' + month;
      if (day.length < 2) 
          day = '0' + day;

      return [year, month, day].join('-');
  }

      init_events($('#external-events div.external-event'))
      
      /* initialize the calendar
       -----------------------------------------------------------------*/
      //Date for the calendar events (dummy data)
      var date = new Date()
      var d    = date.getDate(),
          m    = date.getMonth(),
          y    = date.getFullYear()
      var events = []; //The array Event
      for(var i = 0; i <= '{{ $daycount }}'; i++) 
      {
        // if("{{ count($zooms)}}" < '1')
        // {
        //     events.push( {
        //     title: 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
        //     start: new Date(y, m, i),
        //     backgroundColor: '#dd4b39', //red
        //     borderColor    : '#dd4b39' //red
        //   })
        // }
        // else if("{{ count($zooms)}}" <= '5')
        // {
        //     events.push( {
        //     title: 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
        //     start: new Date(y, m, i),
        //     backgroundColor: '#f39c12', //yellow
        //     borderColor    : '#f39c12' //yellow
        //   })
        // }
        // else if("{{ count($zooms)}}" > '5')
        // {
            var calendate = new Date(y, m, i);
            var booking_date;
            var countdate = 0;
            @foreach($bookings as $booking)
            {
                booking_date = "{{ date('Y-m-d', strtotime($booking->formatted_dob)) }}";

                if(formatDate(calendate) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a' //Success (green)
                  })
                }
                // else if(formatDate(calendate) >= formatDate(date)) //แสดงเฉพาะวันที่ปัจจุปันเป็นต้นไป
                // {
                //     events.push( {
                //     title: 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
                //     start: new Date(y, m, i),
                //     backgroundColor: '#00a65a', //Success (green)
                //     borderColor    : '#00a65a' //Success (green)
                //   })
                // }
                
            }
            @endforeach
            //  if(formatDate(calendate) >= formatDate(date)) //แสดงเฉพาะวันที่ปัจจุปันเป็นต้นไป
            //     {
            //         events.push( {
            //         title: 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
            //         start: new Date(y, m, i),
            //         backgroundColor: '#00a65a', //Success (green)
            //         borderColor    : '#00a65a' //Success (green)
            //       })
            //     }            
          //   events.push( {
          //   title: 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
          //   start: new Date(y, m, i),
          //   backgroundColor: '#00a65a', //Success (green)
          //   borderColor    : '#00a65a' //Success (green)
          // })
        // }
      }

      $('#calendar').fullCalendar({
        header    : {
          left  : 'prev,next today',
          center: 'title',
          right : 'month,agendaWeek,agendaDay'
        },
        buttonText: {
          today: 'today',
          month: 'month',
          week : 'week',
          day  : 'day'
        },
        height: 470,
        events: events,
        // //Random default events
        // events    : [
        //   {
        //     title          : 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
        //     start          : new Date(y, m, 1),
        //     backgroundColor: '#00a65a', //Success (green)
        //     borderColor    : '#00a65a' //Success (green)
        //   },
        //   {
        //     title          : 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
        //     start          : new Date(y, m, 2),
        //     backgroundColor: '#00a65a', //Success (green)
        //     borderColor    : '#00a65a' //Success (green)
        //   },
        //   {
        //     title          : 'จำนวนบัญชีที่ว่าง {{ count($zooms)}} บัญชี',
        //     start          : new Date(y, m, 10),
        //     backgroundColor: '#00a65a', //Success (green)
        //     borderColor    : '#00a65a' //Success (green)
        //   }
        //   // {
        //   //   title          : 'Long Event',
        //   //   start          : new Date(y, m, d - 5),
        //   //   end            : new Date(y, m, d - 2),
        //   //   backgroundColor: '#f39c12', //yellow
        //   //   borderColor    : '#f39c12' //yellow
        //   // },
        //   // {
        //   //   title          : 'Meeting',
        //   //   start          : new Date(y, m, d, 10, 30),
        //   //   allDay         : false,
        //   //   backgroundColor: '#0073b7', //Blue
        //   //   borderColor    : '#0073b7' //Blue
        //   // },
        //   // {
        //   //   title          : 'Lunch',
        //   //   start          : new Date(y, m, d, 12, 0),
        //   //   end            : new Date(y, m, d, 14, 0),
        //   //   allDay         : false,
        //   //   backgroundColor: '#00c0ef', //Info (aqua)
        //   //   borderColor    : '#00c0ef' //Info (aqua)
        //   // },
        //   // {
        //   //   title          : 'Birthday Party',
        //   //   start          : new Date(y, m, d + 1, 19, 0),
        //   //   end            : new Date(y, m, d + 1, 22, 30),
        //   //   allDay         : false,
        //   //   backgroundColor: '#00a65a', //Success (green)
        //   //   borderColor    : '#00a65a' //Success (green)
        //   // },
        //   // {
        //   //   title          : 'Click for Google',
        //   //   start          : new Date(y, m, 28),
        //   //   end            : new Date(y, m, 29),
        //   //   url            : 'http://google.com/',
        //   //   backgroundColor: '#3c8dbc', //Primary (light-blue)
        //   //   borderColor    : '#3c8dbc' //Primary (light-blue)
        //   // }
        // ],
        //selectable: true,
        dayClick: function(date) {
          // alert('clicked ' + date.format());
          location.href = "booklists?date="+date.format();
        },
        // select: function(startDate, endDate) {
        //   alert('selected ' + startDate.format() + ' to ' + endDate.format());
        // },
        editable  : true,
        droppable : true, // this allows things to be dropped onto the calendar !!!
        drop      : function (date, allDay) { // this function is called when something is dropped
      
          // retrieve the dropped element's stored Event Object
          var originalEventObject = $(this).data('eventObject')
      
          // we need to copy it, so that multiple events don't have a reference to the same object
          var copiedEventObject = $.extend({}, originalEventObject)
      
          // assign it the date that was reported
          copiedEventObject.start           = date
          copiedEventObject.allDay          = allDay
          copiedEventObject.backgroundColor = $(this).css('background-color')
          copiedEventObject.borderColor     = $(this).css('border-color')
      
          // render the event on the calendar
          // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
          $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)
      
          // is the "remove after drop" checkbox checked?
          if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            $(this).remove()
          }
      
        }
        // ,
        // dayRender: function (date, cell) {
        // var today = new Date();
        // var end = new Date();
        // end.setDate(today.getDate()+1);

        // // if (d === today.getDate()) {
        // //     cell.css("background-color", "#F9EBEA");
        // // }

        // if(date > today && date <= end) {
        //     cell.css("background-color", "#bce8f1");
        // }

        // }
      })
      
      /* ADDING EVENTS */
      var currColor = '#3c8dbc' //Red by default
      //Color chooser button
      var colorChooser = $('#color-chooser-btn')
      $('#color-chooser > li > a').click(function (e) {
        e.preventDefault()
        //Save color
        currColor = $(this).css('color')
        //Add color effect to button
        $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
      })
      $('#add-new-event').click(function (e) {
        e.preventDefault()
        //Get value and make sure it is not null
        var val = $('#new-event').val()
        if (val.length == 0) {
          return
        }
      
        //Create events
        var event = $('<div />')
        event.css({
          'background-color': currColor,
          'border-color'    : currColor,
          'color'           : '#fff'
        }).addClass('external-event')
        event.html(val)
        $('#external-events').prepend(event)
      
        //Add draggable funtionality
        init_events(event)
      
        //Remove event from text input
        $('#new-event').val('')
      })
      })
      
    </script>
@stop
{{-- @endsection --}}