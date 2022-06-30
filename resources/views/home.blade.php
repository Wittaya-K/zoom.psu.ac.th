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
  .fc-day-grid-event 
  {
    margin: 20px 2px 0;
  }
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
                  <div class="col-md-12">
                      <div id="calendar">
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  {{-- <div class="row">
    <div class="col-md-10">
      <div class="panel panel-default">
        <div class="panel-heading">หมายเหตุ</div>

        <div class="panel-body">
            <div class="col-md-4">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <th>สถานะ</th>
                    <th></th>
                  </tr>
                  <tr>
                    <td>เก่า</td>
                    <td style="background: #7B7D7D"></td>
                  </tr>
                  <tr>
                    <td>ปัจจุบัน</td>
                    <td style="background: #00a65a"></td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
    </div>
    </div>
  </div> --}}
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
      for(var i = 1; i <= '{{ $daycount }}'; i++) 
      {

            var calendate = new Date(y, m, i);
            var nextmonth_1 = new Date(y, m+1, i);
            var nextmonth_2 = new Date(y, m+2, i);
            var nextmonth_3 = new Date(y, m+3, i);
            var nextmonth_4 = new Date(y, m+4, i);
            var nextmonth_5 = new Date(y, m+5, i);
            var nextmonth_6 = new Date(y, m+6, i);
            var nextmonth_7 = new Date(y, m+7, i);
            var nextmonth_8 = new Date(y, m+8, i);
            var nextmonth_9 = new Date(y, m+9, i);
            var nextmonth_10 = new Date(y, m+10, i);
            var nextmonth_11 = new Date(y, m+11, i);
            var nextmonth_12 = new Date(y, m+12, i);

            var booking_date;
            var countdate = 0;
            @foreach($oldbookings as $oldbooking)
                oldbooking_date = "{{ $oldbooking->formatted_dob }}";
                if(formatDate(calendate) == oldbooking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี( เก่า)',
                    start: new Date(y, m, i),
                    backgroundColor: '#7B7D7D', //Success (green)
                    borderColor    : '#7B7D7D', //Success (green)
                    url: 'booklists?date='+oldbooking_date
                  })
                }
            @endforeach
            @foreach($bookings as $booking)
            {
                booking_date = "{{ $booking->formatted_dob }}";

                if(formatDate(calendate) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_1) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+1, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_2) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+2, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_3) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+3, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_4) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+4, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_5) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+5, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_6) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+6, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_7) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+7, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_8) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+8, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_9) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+9, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_10) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+10, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_11) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+11, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }
                if(formatDate(nextmonth_12) == booking_date)
                {
                    // countdate += countdate + 1;
                    events.push( {
                    title: 'มีรายการจองบัญชี',
                    start: new Date(y, m+12, i),
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a', //Success (green)
                    url: 'booklists?date='+booking_date
                  })
                }                
            }
            @endforeach
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
        height: 600,
        events: events,
        dayClick: function(date) {
          location.href = "booklists?date="+date.format();
        },
      })
      })
      
    </script>
@stop