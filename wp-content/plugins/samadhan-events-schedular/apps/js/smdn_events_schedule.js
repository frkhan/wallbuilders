function NextPage(){
    var nextPage = parseInt(jQuery('#currentPageNumber').val()) + 1;
    jQuery('#currentPageNumber').val(nextPage);
    jQuery('#searchButton').click();
}

function PrevPage(){
    var nextPage = parseInt(jQuery('#currentPageNumber').val()) - 1;
    if(nextPage < 0) nextPage = 0;
    jQuery('#currentPageNumber').val(nextPage);
    jQuery('#searchButton').click();
}



/**************Start Event  Section*********************/
    var app = new Vue({
        'el': '#smdnEvents',
        data: {
            // controls:{
            //     loaded:false,
            // },
            events: {
                event: '',
                type: '',
                speaker: '',
                startDate: '',
                endDate: '',
                organization: '',
                contact: '',
                deadline: '',
                setting: '',
                address: '',
                airport: '',
                attendence: '',
                audience: '',
                involvement: '',
                participants: '',
                declined: '',
            },
            setEventId:''
        },


        methods: {
            editEvetById:function (eventId){

                var $apiUrl = settingObject.root + 'samadhan_events/v1/getEventDataById?_wpnonce=' + settingObject.nonce;
                var $data = {setEventId: eventId};

                $jsonData = JSON.stringify($data);
                jQuery.post( $apiUrl,$data).done(function (response) {
                                console.log(response.getEvent);

                    })
                    .fail(function (response) {
                        console.log(response);
                    })
                    .always(function (response) {
                        console.log(response);
                    });
            },
            eventSubmitButton: function () {
                //var $apiUrl = settingObject.root + 'eventSchedular/saveData?_wpnonce=' + settingObject.nonce;
                var $data = {
                    eventsevent: this.events.event,
                    eventstype: this.events.type,
                    speaker: this.events.speaker,
                    startDate: this.events.startDate,
                    endDate: this.events.endDate,
                    organization: this.events.organization,
                    contact: this.events.contact,
                    deadline: this.events.deadline,
                    setting: this.events.setting,
                    address: this.events.address,
                    airport: this.events.airport,
                    attendence: this.events.attendence,
                    audience: this.events.audience,
                    involvement: this.events.involvement,
                    participants: this.events.participants,
                    declined: this.events.declined,
                };

                $jsonData = JSON.stringify($data);

                jQuery.ajax({
                    url: wpApiSettings.root + 'samadhan_events/v1/save_events',
                    method: 'post',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                    },
                    data: $data,

                })
                    .done(function (response) {
                        console.log(response);
                    })
                    .fail(function (response) {
                        console.log(response);
                    })
                    .always(function (response) {
                        console.log(response);
                    });
            },
        },
        created:function(){

            var eventId =jQuery('#setEventId').val();
            if(eventId && eventId > 0) {
                this.editEvetById(eventId);
            }



        }

    });
    window.app = app;
    var appEventInfo = new Vue({
        'el': '#EventsInformation',
        data: {
            controls: {
                loaded: false,
                message: '',
                notification: '',
                buttonDisable:true,
            },
            notification: {
                name: '',
                eventType: '',
                setting: '',
                address: '',
                city: '',
                state: '',
                zip: '',
                closestAirport: '',
                travelToFromAirport: '',
                eventStartDate: '',
                eventStartTime: '',
                eventEndDate: '',
                eventEndTime: '',

                ExpectedAttendance: '',
                descriptionAudience: '',
                speakerPreference: '',
                alternateSpeaker: '',
                speakersInvolvement: '',
                otherParticipants: '',
                eventDeadline: '',
                hearAboutUs: '',
                eventComments: '',
            },

            eventsInfo: {
                name: '',
                eventType: '',
                setting: '',
                address: '',
                city: '',
                state: '',
                zip: '',
                closestAirport: '',
                travelToFromAirport: '',
                eventStartDate: new Date().toISOString().substr(0, 10),
                eventStartTime: '12:00 AM',
                eventEndDate: new Date().toISOString().substr(0, 10),
                eventEndTime: '12:00 AM',

                ExpectedAttendance: '',
                descriptionAudience: '',
                speakerPreference: '',
                alternateSpeaker: '',
                speakersInvolvement: '',
                otherParticipants: '',
                eventDeadline: new Date().toISOString().substr(0, 10),
                hearAboutUs: '',
                eventComments: '',
            },
            conditions:{
                terms1: false,
                terms2: false,
                terms3: false,
                terms4: false,
                terms5: false,
                terms6: false,
                terms7: false,
                terms8: false,

            },
            getStatesValues:[],


        },


        methods: {

            eventInfoSubmitButton: function () {
                this.controls.loader = true;

                if (this.eventsInfo.name === '') {
                    this.notification.name = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.name = '';
                }
                if (this.eventsInfo.eventType === '') {
                    this.notification.eventType = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.eventType = '';
                }
                if (this.eventsInfo.setting === '') {
                    this.notification.setting = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.setting = '';
                }
                if (this.eventsInfo.address === '') {
                    this.notification.address = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.address = '';
                }
                if (this.eventsInfo.closestAirport === '') {
                    this.notification.closestAirport = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.closestAirport = '';
                }
                if (this.eventsInfo.eventStartDate === '') {
                    this.notification.eventStartDate = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.eventStartDate = '';
                }
                if (this.eventsInfo.eventStartTime === '') {
                    this.notification.eventStartTime = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.eventStartTime = '';
                }
                if (this.eventsInfo.eventEndDate === '') {
                    this.notification.eventEndDate = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.eventEndDate = '';
                }
                if (this.eventsInfo.eventEndTime === '') {
                    this.notification.eventEndTime = 'Please enter text in text box above';
                    return falseget_country_states;
                } else {
                    this.notification.eventEndTime = '';
                }
                if (this.eventsInfo.ExpectedAttendance === '') {
                    this.notification.ExpectedAttendance = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.ExpectedAttendance = '';
                }

                if (this.eventsInfo.descriptionAudience === '') {
                    this.notification.descriptionAudience = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.descriptionAudience = '';
                }
                if (this.eventsInfo.speakerPreference === '') {
                    this.notification.speakerPreference = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.speakerPreference = '';
                }
                if (this.eventsInfo.alternateSpeaker === '') {
                    this.notification.alternateSpeaker = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.alternateSpeaker = '';
                }
                if (this.eventsInfo.speakersInvolvement === '') {
                    this.notification.speakersInvolvement = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.speakersInvolvement = '';
                }
                if (this.eventsInfo.otherParticipants === '') {
                    this.notification.otherParticipants = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.otherParticipants = '';
                }
                if (this.eventsInfo.eventDeadline === '') {
                    this.notification.eventDeadline = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.eventDeadline = '';
                }
                if (this.eventsInfo.hearAboutUs === '') {
                    this.notification.hearAboutUs = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.hearAboutUs = '';
                }


                var $apiUrl = settingObject.root + 'samadhan_events/v1/save_eventsInformation?_wpnonce=' + settingObject.nonce;
                var $data = {
                    post: [{eventsInfoName: this.eventsInfo.name},
                        {eventsInfoDescriptionAudience: this.eventsInfo.descriptionAudience}
                    ],
                    postMeta: [{eventsInfoName: this.eventsInfo.name},
                        {eventsInfoEventType: this.eventsInfo.eventType},
                        {eventsInfoSetting: this.eventsInfo.setting},
                        {eventsInfoAddress: this.eventsInfo.address},
                        {eventsInfoCity: this.eventsInfo.city},
                        {eventsInfoState: this.eventsInfo.state},
                        {eventsInfoZip: this.eventsInfo.zip},
                        {eventsInfoClosestAirport: this.eventsInfo.closestAirport},
                        {eventsInfoTravelToFromAirport: this.eventsInfo.travelToFromAirport},
                        {eventsInfoStartDate: this.eventsInfo.eventStartDate},
                        {eventsInfoStartTime: this.eventsInfo.eventStartTime},

                        {eventsInfoEndtDate: this.eventsInfo.eventEndDate},
                        {eventsInfoEndTime: this.eventsInfo.eventEndTime},
                        {eventsInfoExpectedAttendance: this.eventsInfo.ExpectedAttendance},


                        {eventsInfoSpeakerPreference: this.eventsInfo.speakerPreference},
                        {eventsInfoAlternateSpeaker: this.eventsInfo.alternateSpeaker},
                        {eventsInfoSpeakersInvolvement: this.eventsInfo.speakersInvolvement},
                        {eventsInfoOtherParticipants: this.eventsInfo.otherParticipants},
                        {eventsInfoDeadline: this.eventsInfo.eventDeadline},
                        {eventsInfoHearAboutUs: this.eventsInfo.hearAboutUs},
                        {eventsInfoComments: this.eventsInfo.eventComments},
                        {eventsTerms1: this.conditions.terms1},
                        {eventsTerms2: this.conditions.terms2},
                        {eventsTerms3: this.conditions.terms3},
                        {eventsTerms4: this.conditions.terms4},
                        {eventsTerms5: this.conditions.terms5},
                        {eventsTerms6: this.conditions.terms6},
                        {eventsTerms7: this.conditions.terms7},
                        {eventsTerms8: this.conditions.terms8},]
                };

                $jsonData = JSON.stringify($data);

                // jQuery.ajax( {
                //     url: settingObject.root+'samadhan_events/v1/save_eventsInformation',
                //     method: 'post',
                //     beforeSend: function ( xhr ) {
                //         xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                //     },
                //     data:$data,
                //
                // })
                 jQuery.post($apiUrl, $jsonData)
                    .done(function (response) {
                        window.appEventInfo.controls.message = response.status;
                        window.appEventInfo.controls.loader = false;
                    })
                    .fail(function (response) {
                        console.log(response);
                    })
                    .always(function (response) {
                        console.log(response);
                    });
            },
            checkedStatus: function (e) {
               if(e==1){
                   //alert(this.conditions.terms1)
                    if(this.conditions.terms1==true){
                        this.conditions.terms1=false;
                    }else{
                        this.conditions.terms1=true;
                    }
               }
                if(e==2){
                    if(this.conditions.terms2==true){
                        this.conditions.terms2=false;
                    }else{
                        this.conditions.terms2=true;
                    }
                }
                if(e==3){
                        if(this.conditions.terms3==true){
                            this.conditions.terms3=false;
                        }else{
                            this.conditions.terms3=true;
                     }
                }
                if(e==4){
                    if(this.conditions.terms4==true){
                        this.conditions.terms4=false;
                    }else{
                        this.conditions.terms4=true;
                    }
                }
                if(e==5){
                    if(this.conditions.terms5==true){
                        this.conditions.terms5=false;
                    }else{
                        this.conditions.terms5=true;
                    }
                }
                if(e==6){
                    if(this.conditions.terms6==true){
                        this.conditions.terms6=false;
                    }else{
                        this.conditions.terms6=true;
                    }
                }
                if(e==7){
                    if(this.conditions.terms7==true){
                        this.conditions.terms7=false;
                    }else{
                        this.conditions.terms7=true;
                    }
                }
                if(e==8){
                    if(this.conditions.terms8==true){
                        this.conditions.terms8=false;
                    }else{
                        this.conditions.terms8=true;
                    }
                }

         var cond=(this.conditions.terms1
            && this.conditions.terms2
            && this.conditions.terms3
            && this.conditions.terms4
            && this.conditions.terms5
            && this.conditions.terms6
            && this.conditions.terms7
            && this.conditions.terms8);

                if(cond){
                    this.controls.buttonDisable=false;
                }else{
                    this.controls.buttonDisable=true;
                }

            },
            getStates: function (){
                var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
                var jqxhr = jQuery.get($apiUrl)
                    .done(function (response) {

                       appEventInfo.getStatesValues = response.states;
                       appEventInfo.controls.loader = false;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });

            }
        },
        created:function(){
            this.getStates();

        },


    });
    window.appEventInfo = appEventInfo;


    var appContactInfo = new Vue({
        'el': '#ContactInformation',
        data: {
            controls: {
                loaded: false,
                message: '',
                notification: ''
            },
            notification: {
                RequestSpeaker: '',
                Organization: '',
                Title: '',
                FirstName: '',
                LastName: '',
                Pastor: '',
                MailingAddress: '',
                City: '',
                State: '',
                Zip: '',
                Phone: '',
                CellPhone: '',
                Fax: '',
                EmailAddress: '',

            },

            Contacts: {
                RequestSpeaker: '',
                Organization: '',
                Title: '',
                FirstName: '',
                LastName: '',
                Pastor: '',
                MailingAddress: '',
                City: '',
                State: '',
                Zip: '',
                Phone: '',
                CellPhone: '',
                Fax: '',
                EmailAddress: '',

            },
            getStatesValues:[],

        },


        methods: {

            contactSubmitButton: function () {

                this.controls.loader = true;
                if (this.Contacts.RequestSpeaker === '') {
                    this.notification.FirstName = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.FirstName = '';
                }
                if (this.Contacts.FirstName === '') {
                    this.notification.FirstName = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.FirstName = '';
                }
                if (this.Contacts.LastName === '') {
                    this.notification.LastName = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.LastName = '';
                }
                if (this.Contacts.MailingAddress === '') {
                    this.notification.MailingAddress = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.MailingAddress = '';
                }
                if (this.Contacts.City === '') {
                    this.notification.City = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.City = '';
                }
                if (this.Contacts.State === '') {
                    this.notification.State = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.State = '';
                }
                if (this.Contacts.Zip === '') {
                    this.notification.Zip = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.Zip = '';
                }
                if (this.Contacts.Phone === '') {
                    this.notification.Phone = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.Phone = '';
                }
                if (this.Contacts.CellPhone === '') {
                    this.notification.CellPhone = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.CellPhone = '';
                }
                if (this.Contacts.EmailAddress === '') {
                    this.notification.EmailAddress = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.EmailAddress = '';
                }


                var $apiUrl = settingObject.root + 'samadhan_events/v1/save_contactInformation?_wpnonce=' + settingObject.nonce;
                var $data = {
                    user: [
                        {ContactsFirstName: this.Contacts.FirstName},
                        {ContactsLastName: this.Contacts.LastName},
                        {ContactsEmailAddress: this.Contacts.EmailAddress},
                    ],
                    userMeta: [
                        {ContactsRequestSpeaker: this.Contacts.RequestSpeaker},
                        {ContactsOrganization: this.Contacts.Organization},
                        {ContactsTitle: this.Contacts.Title},
                        {ContactsFirstName: this.Contacts.FirstName},
                        {ContactsLastName: this.Contacts.LastName},
                        {ContactsPastor: this.Contacts.Pastor},
                        {ContactsMailingAddress: this.Contacts.MailingAddress},
                        {ContactsCity: this.Contacts.City},
                        {ContactsState: this.Contacts.State},
                        {ContactsZip: this.Contacts.Zip},
                        {ContactsPhone: this.Contacts.Phone},
                        {ContactsCellPhone: this.Contacts.CellPhone},
                        {ContactsFax: this.Contacts.Fax},
                        {ContactsEmailAddress: this.Contacts.EmailAddress},
                    ]
                };

                $jsonData = JSON.stringify($data);

                jQuery.post($apiUrl, $jsonData)
                    .done(function (response) {
                        window.appContactInfo.controls.message = response.status;
                        window.appContactInfo.controls.loader = false;
                    })
                    .fail(function (response) {
                        console.log(response);

                    })
                    .always(function (response) {
                        console.log(response);
                    });
            },
            getStates: function (){
                var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
               jQuery.get($apiUrl)
                    .done(function (response) {

                        appContactInfo.getStatesValues = response.states;
                        appContactInfo.controls.loader = false;
                    })
                    .fail(function (response) {
                        console.log(response);
                    })
                    .always(function (response) {
                        console.log(response);
                    });

            }
        },
        created:function(){
            this.getStates();

        }


    });
    window.appContactInfo = appContactInfo;


    var appLeaderInfo = new Vue({
        'el': '#LeaderInformation',
        data: {
            controls: {
                loaded: false,
                message: '',
                notification: ''
            },
            notification: {
                Title: '',
                FirstName: '',
                LastName: '',
                Pastor: '',
                MailingAddress: '',
                City: '',
                State: '',
                Zip: '',
                Phone: '',
                CellPhone: '',
                EmailAddress: '',

            },
            Leaders: {
                // requestSpeaker: '',
                //Organization: '',
                Title: '',
                FirstName: '',
                LastName: '',
                Pastor: '',
                MailingAddress: '',
                City: '',
                State: '',
                Zip: '',
                Phone: '',
                CellPhone: '',
                EmailAddress: '',

            },
            getStatesValues:[],

        },


        methods: {

            leadersSubmitButton: function () {
                this.controls.loader = true;
                if (this.Leaders.FirstName == '') {
                    this.notification.FirstName = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.FirstName = '';
                }
                if (this.Leaders.LastName == '') {
                    this.notification.LastName = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.LastName = '';
                }
                if (this.Leaders.MailingAddress == '') {
                    this.notification.MailingAddress = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.MailingAddress = '';
                }
                if (this.Leaders.City == '') {
                    this.notification.City = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.City = '';
                }
                if (this.Leaders.State == '') {
                    this.notification.State = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.State = '';
                }
                if (this.Leaders.Zip == '') {
                    this.notification.Zip = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.Zip = '';
                }
                if (this.Leaders.EmailAddress == '') {
                    this.notification.EmailAddress = 'Please enter text in text box above';
                    return false;
                } else {
                    this.notification.EmailAddress = '';
                }


                var $apiUrl = settingObject.root + 'samadhan_events/v1/save_leadersInformation?_wpnonce=' + settingObject.nonce;
                var $data = {
                    user: [
                        {LeadersFirstName: this.Leaders.FirstName},
                        {LeadersLastName: this.Leaders.LastName},
                        {LeadersEmailAddress: this.Leaders.EmailAddress},
                    ],
                    userMeta: [{LeadersTitle: this.Leaders.Title},
                        {LeadersFirstName: this.Leaders.FirstName},
                        {LeadersLastName: this.Leaders.LastName},
                        {LeadersPastor: this.Leaders.Pastor},
                        {LeadersMailingAddress: this.Leaders.MailingAddress},
                        {LeadersCity: this.Leaders.City},
                        {LeadersState: this.Leaders.State},
                        {LeadersZip: this.Leaders.Zip},
                        {LeadersPhone: this.Leaders.Phone},
                        {LeadersCellPhone: this.Leaders.CellPhone},
                        {LeadersEmailAddress: this.Leaders.EmailAddress},
                    ]
                };

                $jsonData = JSON.stringify($data);

                // jQuery.ajax( {
                //     url: settingObject.root+'samadhan_events/v1/save_eventsInformation',
                //     method: 'post',
                //     beforeSend: function ( xhr ) {
                //         xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                //     },
                //     data:$data,
                //
                // })
                var jqxhr = jQuery.post($apiUrl, $jsonData)
                    .done(function (response) {
                        window.appLeaderInfo.controls.message = response.status;
                        window.appLeaderInfo.controls.loader = false;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });
            },
            getStates: function (){
                var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
                var jqxhr = jQuery.get($apiUrl)
                    .done(function (response) {

                        appLeaderInfo.getStatesValues = response.states;
                        appLeaderInfo.controls.loader = false;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });

            }
        },
        created:function(){
            this.getStates();

        },

    });
    window.appLeaderInfo = appLeaderInfo;


    var manageEventsRequestReport = new Vue({
        'el': '#reportShowTable',
        data: {
            controls: {
                loaded: false,
                message: '',
                notification: '',
                baseUrl: ''
            },
            paginationBlock:false,
            totalPages:0,
            perPage:20,
            searchValue:'',
            getAllEventValues:[],
            getPageList:[20,30,40,50],
            page: 1,
            homeUrl:' ',
            pages: [],
            getStatesValues:[],
            getSpeakerValues:[],
            eventfilter:{
                Event: '',
                EventType: '',
                Organization: '',
                City: '',
                State: '',
                SpeakerRequestId: '',
                Status: '',
                FirstName: '',
                LastName: '',
                Email: '',
                ContactDate: '',


            }

        },


        methods: {

            eventsRequestFilter:function (){
                this.controls.loader = true;
                this.getAllEventValues=[];

                if(this.eventfilter.Event==''){
                    var Event ='**';
                }else{
                    Event=this.eventfilter.Event;
                }
                if(this.eventfilter.EventType==''){
                    var EventType ='**';
                }else{
                    EventType=this.eventfilter.EventType;
                }
                if(this.eventfilter.Organization==''){
                    var Organization ='**';
                }else{
                    Organization=this.eventfilter.Organization;
                }
                if(this.eventfilter.City==''){
                    var City ='**';
                }else{
                    City=this.eventfilter.City;
                }
                if(this.eventfilter.State==''){
                    var State ='**';
                }else{
                    State=this.eventfilter.State;
                }
                if(this.eventfilter.SpeakerRequestId==''){
                    var SpeakerRequestId ='**';
                }else{
                    SpeakerRequestId=this.eventfilter.SpeakerRequestId;
                }
                if(this.eventfilter.Status==''){
                    var Status ='**';
                }else{
                    Status=this.eventfilter.Status;
                }
                if(this.eventfilter.FirstName==''){
                    var FirstName ='**';
                }else{
                    FirstName=this.eventfilter.FirstName;
                }
                if(this.eventfilter.LastName==''){
                    var LastName ='**';
                }else{
                    LastName=this.eventfilter.LastName;
                }
                if(this.eventfilter.Email==''){
                    var Email ='**';
                }else{
                    Email=this.eventfilter.Email;
                }
                if(this.eventfilter.ContactDate==''){
                    var ContactDate ='**';
                }else{
                    ContactDate=this.eventfilter.ContactDate;
                }



                var  Data={
                EventsFilter:[
                {Event: Event},
                {EventType: EventType},
                {Organization: Organization},
                {City: City},
                {State: State},
                {SpeakerRequestId: SpeakerRequestId},
                {Status: Status},
                {FirstName: FirstName},
                {LastName: LastName},
                {Email: Email},
                {ContactDate:ContactDate},
                    ]
                };

                var $data=JSON.stringify(Data);

                var $apiUrl = settingObject.root + 'samadhan_events/v1/eventRequstFilter?_wpnonce=' + settingObject.nonce;
                jQuery.post( $apiUrl,$data)
                    .done(function (response) {
                        manageEventsRequestReport.getAllEventValues = response.allReports;
                        manageEventsRequestReport.totalPages = response.totalEvent;
                        window.manageEventsRequestReport.controls.loader = false;
                        window.manageEventsRequestReport.paginationBlock = true;
                        window.manageEventsRequestReport.controls.baseUrl = settingObject.root;

                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });
            },




            postPageEntities:function (){
                var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
                this.controls.loader = true;
                var $apiUrl = settingObject.root + 'samadhan_events/v1/perPageEventShow?_wpnonce=' + settingObject.nonce;
                jQuery.post( $apiUrl,$data)
                    .done(function (response) {
                        manageEventsRequestReport.getAllEventValues = response.allReports;
                        manageEventsRequestReport.totalPages = response.totalEvent;
                        window.manageEventsRequestReport.controls.loader = false;
                        window.manageEventsRequestReport.paginationBlock = true;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });
            },

            searchEvents: function (){
                var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
                this.controls.loader = true;
                var $apiUrl = settingObject.root + 'samadhan_events/v1/searchEventValue?_wpnonce=' + settingObject.nonce;
                jQuery.post( $apiUrl,$data)
                    .done(function (response) {
                        manageEventsRequestReport.getAllEventValues = response.allReports;
                        manageEventsRequestReport.totalPages = response.totalEvent;
                        window.manageEventsRequestReport.controls.loader = false;
                        window.manageEventsRequestReport.paginationBlock = true;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });

            },
            getAllEventsRequestReports: function (){
                this.controls.loader = true;
                this.homeUrl=settingObject.homeUrl+'edit-event/?event_id=';
                var $apiUrl = settingObject.root + 'samadhan_events/v1/get_all_event_request?_wpnonce=' + settingObject.nonce;
                var jqxhr = jQuery.get($apiUrl)
                    .done(function (response) {
                   // console.log(response.allReports)

                        manageEventsRequestReport.getAllEventValues = response.allReports;
                        manageEventsRequestReport.totalPages = response.totalEvent;
                        window.manageEventsRequestReport.controls.loader = false;
                        window.manageEventsRequestReport.paginationBlock = true;
                        window.manageEventsRequestReport.controls.baseUrl = settingObject.root;
                    })
                    .fail(function (responsose) {
                    })
                    .always(function (response) {

                    });

            },
            setPage: function(pageNumber) {
                this.page = pageNumber
            },
            setPages(){
                let numberOfPages = Math.ceil(this.getAllEventValues.length / this.perPage);
                this.totalPages=0;
                for (let index = 1; index <= numberOfPages; index++) {
                    this.totalPages++;
                    this.pages.push(index);


                }

            },
            paginate (getAllEvent){
                let page = this.page;
                let perPage = this.perPage;
                let from = (page * perPage) - perPage;
                let to = (page * perPage);
                return  getAllEvent.slice(from, to);
            },
            getStates: function (){
                var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
                var jqxhr = jQuery.get($apiUrl)
                    .done(function (response) {

                        manageEventsRequestReport.getStatesValues = response.states;
                        manageEventsRequestReport.controls.loader = false;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });

            },
            getSpeaker: function (){
                var $apiUrl = settingObject.root + 'samadhan_events/v1/get_speaker_all_data?_wpnonce=' + settingObject.nonce;
                var jqxhr = jQuery.get($apiUrl)
                    .done(function (response) {

                        manageEventsRequestReport.getSpeakerValues = response.speaker;
                        manageEventsRequestReport.controls.loader = false;
                    })
                    .fail(function (response) {
                    })
                    .always(function (response) {

                    });

            },
        },

        created:function(){
            this.getAllEventsRequestReports();
            this.getStates();
            this.getSpeaker();

        },
        computed: {
            displayedData () {
                return this.paginate(this.getAllEventValues);
            }
        },
        watch: {
            getAllEventValues () {
                this.setPages();
            }
        },
        filters: {
            trimWords(value){
                return value.split(" ").splice(0,10).join(" ") + '...';
            }
        }

    });
    window.manageEventsRequestReport = manageEventsRequestReport;

  /**************End Event Section*********************/
  /************************************************************/
  /**************Start Schedule Section*********************/
  var SchedularMediaRequestForm = new Vue({

      'el': '#SchedularMediaRequestForm',
      data: {
          controls: {
              loaded: false,
              message: '',
              notification: '',
              buttonDisable:true,
          },
          setMediaRequestId:'',
          organization: {
              redFlag: '',
              active: '',
              mediaType: '',
              station: '',
              name: '',
              type: '',
              audience: '',
              host: '',
              topic: '',
          },
          inverview:{
              date: new Date().toISOString().substr(0, 10),
              hour: '',
              minutes: '',
              amPm: '',
              extraTime: '',
              length: '',
              type: '',
              speaker: '',
          },
          contact: {
              name: '',
              address: '',
              city: '',
              state: '',
              zip: '',
              phone: '',
              phone2: '',
              phone3: '',
              email: '',
          },
          others:{
              notes: '',
              createDate:''
          },

          getStatesValues:[],
          getSpeakerValues:[],


      },
    mounted(){
        var media_request_id=jQuery('#media_request_id').val();
        this.getMediaRequestData(media_request_id);
    },

      methods: {

          getMediaRequestData:function (media_request_id){
//alert(media_request_id)
              var $apiUrl = settingObject.root + 'samadhan_schedule/v1/getMediaRequestByData?_wpnonce=' + settingObject.nonce;

              $jsonData = JSON.stringify({media_request_id:media_request_id});


              var jqxhr = jQuery.post($apiUrl, $jsonData)
                  .done(function (response) {
                     var getData= jQuery.isEmptyObject(response.resutls);
                    if(!getData){
                      var self=window.SchedularMediaRequestForm;

                      //SHOW OR ORGANIZATION

                       self.setMediaRequestId= response.resutls[0].Id;

                      self.organization.redFlag= response.resutls[0].RedFlagId;
                      self.organization.active= response.resutls[0].Active;
                      self.organization.mediaType= response.resutls[0].MediaType;
                      self.organization.station= response.resutls[0].Station;
                      self.organization.name= response.resutls[0].ShowName;
                      self.organization.type= response.resutls[0].ShowType;
                      self.organization.audience= response.resutls[0].LiveAudience;
                      self.organization.host= response.resutls[0].HostName;
                      self.organization.topic= response.resutls[0].Topic;


                      //CONTACT INFORMATION
                      self.contact.name= response.resutls[0].ContactName;
                      self.contact.address= response.resutls[0].Address;
                      self.contact.city= response.resutls[0].City;
                      self.contact.state= response.resutls[0].State;
                      self.contact.zip= response.resutls[0].Zip;
                      self.contact.phone= response.resutls[0].Phone;
                      self.contact.phone2= response.resutls[0].Phone2;
                      self.contact.phone3= response.resutls[0].Phone3;
                      self.contact.email= response.resutls[0].Email;


                      //INTERVIEW

                      self.inverview.date= response.resutls[0].InterviewDateTime;
                      self.inverview.hour= "12";
                      self.inverview.minutes='00';
                      self.inverview.amPm='PM';
                      self.inverview.extraTime= response.resutls[0].TimeZone;
                      self.inverview.length= response.resutls[0].InterviewLength;
                      self.inverview.type= response.resutls[0].InterviewType;
                      self.inverview.speaker= response.resutls[0].SpeakerId;



                      //OTHER INFORMATION
                      self.others.notes= response.resutls[0].RedFlagNotes;
                      self.others.createDate= response.resutls[0].DateCreated;

                     console.log(response.resutls[0])
                      //window.SchedularMediaRequestForm.controls.message = response.status;
                      //window.SchedularMediaRequestForm.controls.loader = false;

                    }
                    })
                  .fail(function (response) {
                  })
                  .always(function (response) {

                  });
          },

          MediaRequestSubmitButton: function () {
              this.controls.loader = true;



              var $apiUrl = settingObject.root + 'samadhan_schedule/v1/saveMediaRequestForm?_wpnonce=' + settingObject.nonce;
              var $data = {
                  updateMediaRequestId:{mediaRequestId:this.setMediaRequestId},
                  MediaRequest: [
                      {ShowName: this.organization.name},
                      {HostName: this.organization.host},
                      {Topic: this.organization.topic},
                      {ShowType: this.organization.type},
                      {InterviewDateTime: this.inverview.date},
                      {TimeZone: this.inverview.extraTime},
                      {ContactName: this.contact.name},
                      {Email: this.contact.email},
                      {InterviewType: this.inverview.type},
                      {LiveAudience: this.organization.audience},
                      {Notes:  this.others.notes},
                      {DateCreated: new Date().toISOString().substr(0, 10)},
                      {SpeakerId: this.inverview.speaker},
                      {InterviewLength: this.inverview.length},
                      {Phone: this.contact.phone},
                      {MediaType: this.organization.mediaType},
                      {Station: this.organization.station},
                      {Active: this.organization.active},
                      {Phone2: this.contact.phone2},
                      {Phone3: this.contact.phone3},
                      {Address: this.contact.address},
                      {City: this.contact.city},
                      {State: this.contact.state},
                      {Zip: this.contact.zip},
                      {RedFlagId: this.organization.redFlag},
                      {RedFlagNotes: this.others.notes},


                  ],

                  userMeta: [{mediaReqOrgActive: this.organization.active},
                      {mediaReqOrgMediaType: this.organization.mediaType},
                      {mediaReqOrgRedFlag: this.organization.redFlag},
                      {mediaReqOrgStation: this.organization.station},
                      {mediaReqOrgName: this.organization.name},
                      {mediaReqOrgType: this.organization.type},
                      {mediaReqOrgAudience: this.organization.audience},
                      {mediaReqOrgHost: this.organization.host},
                      {mediaReqOrgTopic: this.organization.topic},

                      {mediaReqInViewDate: this.inverview.date},
                      {mediaReqInViewHour: this.inverview.hour},
                      {mediaReqInViewMinutes: this.inverview.minutes},
                      {mediaReqInViewAmPm: this.inverview.amPm},
                      {mediaReqInViewExtraTime: this.inverview.extraTime},
                      {mediaReqInViewLength: this.inverview.length},
                      {mediaReqInViewType: this.inverview.type},
                      {mediaReqInViewSpeaker: this.inverview.speaker},

                      {mediaReqContName: this.contact.name},
                      {mediaReqContAddress: this.contact.address},
                      {mediaReqContCity: this.contact.city},
                      {mediaReqContState: this.contact.state},
                      {mediaReqContZip: this.contact.zip},
                      {mediaReqContPhone: this.contact.phone},
                      {mediaReqContPhone2: this.contact.phone2},
                      {mediaReqContPhone3: this.contact.phone3},
                      {mediaReqContEmail: this.contact.email},

                      {mediaReqfoNotes: this.others.Notes},
                      ]
              };

              $jsonData = JSON.stringify($data);


              var jqxhr = jQuery.post($apiUrl, $jsonData)
                  .done(function (response) {

                      window.SchedularMediaRequestForm.controls.message = response.status;
                      window.SchedularMediaRequestForm.controls.loader = false;
                  })
                  .fail(function (response) {
                  })
                  .always(function (response) {

                  });
          },

          getStates: function (){
              var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
              var jqxhr = jQuery.get($apiUrl)
                  .done(function (response) {

                      SchedularMediaRequestForm.getStatesValues = response.states;
                      SchedularMediaRequestForm.controls.loader = false;
                  })
                  .fail(function (response) {
                  })
                  .always(function (response) {

                  });

          },
          getSpeaker: function (){
              var $apiUrl = settingObject.root + 'samadhan_events/v1/get_speaker_all_data?_wpnonce=' + settingObject.nonce;
              var jqxhr = jQuery.get($apiUrl)
                  .done(function (response) {

                      SchedularMediaRequestForm.getSpeakerValues = response.speaker;
                      SchedularMediaRequestForm.controls.loader = false;
                  })
                  .fail(function (response) {
                  })
                  .always(function (response) {

                  });

          },

      },
      created:function(id){

          this.getStates();
          this.getSpeaker();


      },

  });
  window.SchedularMediaRequestForm = SchedularMediaRequestForm;


  var SchedularMediaRequestReport = new Vue({

    'el': '#ManageMediaRequestReport',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            baseUrl: ''
        },
        paginationBlock:false,
        totalPages:0,
        perPage:20,
        searchValue:'',
        getAllEventValues:[],
        getPageList:[20,30,40,50],
        page: 1,
        pageUrl:' ',
        pages: [],
        getSpeakerValues:[],
        filter:{
                ShowName: '',
                ShowType: '',
                HostName: '',
                Topic: '',
                MediaType: '',
                ContactName: '',
                Email: '',
                SpeakerId: '',
                InterviewDateTime:''
             }

    },


    methods: {

        mediaRequestFilter:function(){
            this.controls.loader = true;
            this.getAllEventValues=[];

            if(this.filter.ShowName==''){
                var ShowName ='**';
            }else{
                ShowName=this.filter.ShowName;
            }
            if(this.filter.ShowType==''){
                var ShowType ='**';
            }else{
                ShowType=this.filter.ShowType;
            }
            if(this.filter.HostName==''){
                var HostName ='**';
            }else{
                HostName=this.filter.HostName;
            }
            if(this.filter.Topic==''){
                var Topic ='**';
            }else{
                Topic=this.filter.Topic;
            }
            if(this.filter.MediaType==''){
                var MediaType ='**';
            }else{
                MediaType=this.filter.MediaType;
            }
            if(this.filter.ContactName==''){
                var ContactName ='**';
            }else{
                ContactName=this.filter.ContactName;
            }
            if(this.filter.Email==''){
                var Email ='**';
            }else{
                Email=this.filter.Email;
            }
            if(this.filter.SpeakerId==''){
                var SpeakerId ='**';
            }else{
                SpeakerId=this.filter.SpeakerId;
            }
            if(this.filter.InterviewDateTime==''){
                var InterviewDateTime ='**';
            }else{
                InterviewDateTime=this.filter.InterviewDateTime;
            }


            var Data={
                filterData:[
                    {ShowName:ShowName},
                    {ShowType: ShowType},
                    {HostName:HostName},
                    {Topic: Topic},
                    {MediaType: MediaType},
                    {ContactName: ContactName},
                    {Email: Email},
                    {SpeakerId: SpeakerId},
                    {InterviewDateTime: InterviewDateTime}

                  ]
                    }


            var $data=JSON.stringify(Data);

            var $apiUrl = settingObject.root + 'samadhan_events/v1/mediaRequstFilter?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    SchedularMediaRequestReport.getAllEventValues = response.allReports;
                    SchedularMediaRequestReport.totalPages = response.totalEvent;
                    window.SchedularMediaRequestReport.controls.loader = false;
                    window.SchedularMediaRequestReport.paginationBlock = true;

                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
        postPageEntities:function (){
            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/perPageMediaRequestShow?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    SchedularMediaRequestReport.getAllEventValues = response.allReports;
                    SchedularMediaRequestReport.totalPages = response.totalEvent;
                    window.SchedularMediaRequestReport.controls.loader = false;
                    window.SchedularMediaRequestReport.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

        searchEvents: function (){
            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/searchMediaRequestValue?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    SchedularMediaRequestReport.getAllEventValues = response.allReports;
                    SchedularMediaRequestReport.totalPages = response.totalEvent;
                    window.SchedularMediaRequestReport.controls.loader = false;
                    window.SchedularMediaRequestReport.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
        getAllEventsRequestReports: function (){

            this.controls.loader = true;
            this.pageUrl=settingObject.homeUrl+'edit-media-request/?media_request_id=';

            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_media_request_report?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {
                    // console.log(response.allReports)

                    SchedularMediaRequestReport.getAllEventValues = response.allReports;
                    SchedularMediaRequestReport.totalPages = response.totalEvent;
                    window.SchedularMediaRequestReport.controls.loader = false;
                    window.SchedularMediaRequestReport.paginationBlock = true;
                    window.SchedularMediaRequestReport.controls.baseUrl = settingObject.root;
                })
                .fail(function (responsose) {
                })
                .always(function (response) {

                });

        },
        setPage: function(pageNumber) {
            this.page = pageNumber
        },
        setPages(){
            let numberOfPages = Math.ceil(this.getAllEventValues.length / this.perPage);
            this.totalPages=0;
            for (let index = 1; index <= numberOfPages; index++) {
                this.totalPages++;
                this.pages.push(index);


            }

        },
        paginate (getAllEvent){
            let page = this.page;
            let perPage = this.perPage;
            let from = (page * perPage) - perPage;
            let to = (page * perPage);
            return  getAllEvent.slice(from, to);
        },
        getSpeaker: function (){
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_speaker_all_data?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {

                    SchedularMediaRequestReport.getSpeakerValues = response.speaker;
                    SchedularMediaRequestReport.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
    },
    created:function(){
        this.getAllEventsRequestReports();
        this.getSpeaker();

    },
    computed: {
        displayedData () {
            return this.paginate(this.getAllEventValues);
        }
    },
    watch: {
        getAllEventValues () {
            this.setPages();
        }
    },
    filters: {
        trimWords(value){
            return value.split(" ").splice(0,10).join(" ") + '...';
        }
    }

});
  window.SchedularMediaRequestReport = SchedularMediaRequestReport;




  var createItineraryForm = new Vue({
    'el': '#createItineraryForm',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            buttonDisable:true,
        },
        // notification: {
        //     name: '',
        //     eventType: '',
        //     setting: '',
        //     address: '',
        // },

        createItinerary: {
            startTime: new Date().toISOString().substr(0, 10),
            endTime: new Date().toISOString().substr(0, 10),
            type: '',
        },

    },


    methods: {

        createItinerarySubmitButton: function () {
            this.controls.loader = true;
            // if (this.eventsInfo.name == '') {
            //     this.notification.name = 'Please enter text in text box above';
            //     return false;
            // } else {
            //     this.notification.name = '';
            // }



            var $apiUrl = settingObject.root + 'samadhan_schedule/v1/saveCreateItineraryForm?_wpnonce=' + settingObject.nonce;
            var $data = {
                user: [''],
                userMeta: [{createItineraryStartTime: this.createItinerary.startTime},
                    {createItineraryEndTime: this.createItinerary.endTime},
                    {createItineraryType: this.createItinerary.type},

                ]
            };

            $jsonData = JSON.stringify($data);


            var jqxhr = jQuery.post($apiUrl, $jsonData)
                .done(function (response) {
                    window.createItineraryForm.controls.message = response.status;
                    window.createItineraryForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },


    },
    created:function(){

    },


});
  window.createItineraryForm = createItineraryForm;

  var addManageSpeakersForm = new Vue({
    'el': '#addManageSpeakersForm',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            buttonDisable:true,
        },
        // notification: {
        //     name: '',
        //     eventType: '',
        //     setting: '',
        //     address: '',
        // },

        addSpeaker: {
            nickName: '',
            firstName: '',
            lastName: '',
            email: '',
            updateSpeakerId:''
        },

    },


    methods: {


        editSpeaker:function (speakerId){

            var $apiUrl = settingObject.root + 'samadhan_schedule/v1/getSpeakerDataBySpeakerId?_wpnonce=' + settingObject.nonce;
            var $data = {SpeakerId: speakerId};

            $jsonData = JSON.stringify($data);


            var jqxhr = jQuery.post($apiUrl, $jsonData)
                .done(function (response) {
                    console.log(response.getSpeaker)
                    var getData=jQuery.isEmptyObject(response.getSpeaker);
                    console.log(getData+'kk')
                    var self=window.addManageSpeakersForm;
                    if(!getData){
                        self.addSpeaker.updateSpeakerId=response.getSpeaker[0].Id;
                        self.addSpeaker.firstName=response.getSpeaker[0].FirstName;
                        self.addSpeaker.lastName=response.getSpeaker[0].LastName;
                        self.addSpeaker.nickName=response.getSpeaker[0].Nickname;
                        self.addSpeaker.email=response.getSpeaker[0].user_email;
                    }

                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },
        addSpeakerSubmitButton: function () {


            this.controls.loader = true;
            this.controls.message = '';

            var $apiUrl = settingObject.root + 'samadhan_schedule/v1/saveAddSpeakerForm?_wpnonce=' + settingObject.nonce;
            var $data = {
                speaker: [{FirstName: this.addSpeaker.firstName},
                    {LastName: this.addSpeaker.lastName},
                    {Nickname: this.addSpeaker.nickName},
                    {user_email: this.addSpeaker.email},
                    {update_speakerId: this.addSpeaker.updateSpeakerId}

                ]
            };

            $jsonData = JSON.stringify($data);


            var jqxhr = jQuery.post($apiUrl, $jsonData)
                .done(function (response) {
                    window.addManageSpeakersForm.controls.message = response.status;
                    window.addManageSpeakersForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

    },
    created:function(){
      var speakerId=jQuery('#speakerId').val();
      this.editSpeaker(speakerId);
    },


});
  window.addManageSpeakersForm = addManageSpeakersForm;

  var manageSpeakerListTable = new Vue({
    'el': '#SpeakerListTable',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            baseUrl: ''
        },
        paginationBlock:false,
        totalPages:0,
        perPage:20,
        searchValue:'',
        getAllTeacherValues:[],
        getPageList:[20,30,40,50],
        page: 1,
        //showPerPages: 20,
        pages: [],

        homeUrl:' ',

    },


    methods: {


        postPageEntities:function (){
            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;

            var $apiUrl = settingObject.root + 'samadhan_events/v1/perPageTeacherShow?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    manageSpeakerListTable.getAllTeacherValues = response.speaker;
                    manageSpeakerListTable.totalPages = response.totalSpeaker;
                    window.manageSpeakerListTable.controls.loader = false;
                    window.manageSpeakerListTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

        searchEvents: function (){
            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/searchTeacherValue?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    manageSpeakerListTable.getAllTeacherValues = response.speaker;
                    manageSpeakerListTable.totalPages = response.totalSpeaker;
                    window.manageSpeakerListTable.controls.loader = false;
                    window.manageSpeakerListTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
        getAllget_speaker_all_data: function (){
            this.controls.loader = true;
            this.homeUrl=settingObject.homeUrl+'edit-speaker/?speaker_id=';
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_speaker_all_data?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {
                    // console.log(response.allReports)

                    manageSpeakerListTable.getAllTeacherValues = response.speaker;
                    manageSpeakerListTable.totalPages = response.totalSpeaker;
                    window.manageSpeakerListTable.controls.loader = false;
                    window.manageSpeakerListTable.paginationBlock = true;

                })
                .fail(function (responsose) {
                })
                .always(function (response) {

                });

        },
        setPage: function(pageNumber) {
            this.page = pageNumber
        },
        setPages(){
            let numberOfPages = Math.ceil(this.getAllTeacherValues.length / this.perPage);
            this.totalPages=0;
            for (let index = 1; index <= numberOfPages; index++) {
                this.totalPages++;
                this.pages.push(index);


            }

        },
        paginate (getAllEvent){
            let page = this.page;
            let perPage = this.perPage;
            let from = (page * perPage) - perPage;
            let to = (page * perPage);
            return  getAllEvent.slice(from, to);
        },


    },

    created:function(){
        this.getAllget_speaker_all_data();


    },
    computed: {
        displayedData () {
            return this.paginate(this.getAllTeacherValues);
        }
    },
    watch: {
        getAllTeacherValues () {
            this.setPages();
        }
    },
    filters: {
        trimWords(value){
            return value.split(" ").splice(0,10).join(" ") + '...';
        }
    }

});
  window.manageSpeakerListTable = manageSpeakerListTable;


  var ScheduleRickGreenTable = new Vue({
    'el': '#ScheduleRickGreenTable',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            baseUrl: ''
        },
        paginationBlock:false,
        totalPages:0,
        perPage:20,
        searchValue:'',
        getAllEventValues:[],
        getPageList:[20,30,40,50],
        page: 1,
        //showPerPages: 20,
        pages: [],
        getSpeakerValues:[],
        SpeakerId:'all'



    },


    methods: {


        filterScheduleGreenSpeaker:function (){

            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage,SpeakerId:this.SpeakerId});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/EventsFilterspeaker?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    ScheduleRickGreenTable.getAllEventValues = response.speakerGreen;
                    ScheduleRickGreenTable.totalPages = response.totalReports;
                    window.ScheduleRickGreenTable.controls.loader = false;
                    window.ScheduleRickGreenTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },
        postPageEntities:function (){
            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/perPageEvents_green_speakerShow?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    ScheduleRickGreenTable.getAllEventValues = response.speakerGreen;
                    ScheduleRickGreenTable.totalPages = response.totalReports;
                    window.ScheduleRickGreenTable.controls.loader = false;
                    window.ScheduleRickGreenTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

        searchScheduleGreen: function (){

            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/searchevEnts_green_speakerValue?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    ScheduleRickGreenTable.getAllEventValues = response.speakerGreen;
                    ScheduleRickGreenTable.totalPages = response.totalReports;
                    window.ScheduleRickGreenTable.controls.loader = false;
                    window.ScheduleRickGreenTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
        getAllEventsGreenSpeakerReports: function (){
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_schedule_events_green_speaker_data?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {
                    // console.log(response.allReports)

                    ScheduleRickGreenTable.getAllEventValues = response.speakerGreen;
                    ScheduleRickGreenTable.totalPages = response.totalReports;
                    window.ScheduleRickGreenTable.controls.loader = false;
                    window.ScheduleRickGreenTable.paginationBlock = true;

                })
                .fail(function (responsose) {
                })
                .always(function (response) {

                });

        },
        setPage: function(pageNumber) {
            this.page = pageNumber
        },
        setPages(){
            let numberOfPages = Math.ceil(this.getAllEventValues.length / this.perPage);
            this.totalPages=0;
            for (let index = 1; index <= numberOfPages; index++) {
                this.totalPages++;
                this.pages.push(index);


            }

        },
        paginate (getAllEvent){
            let page = this.page;
            let perPage = this.perPage;
            let from = (page * perPage) - perPage;
            let to = (page * perPage);
            return  getAllEvent.slice(from, to);
        },

        getSpeaker: function (){
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_speaker_all_data?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {

                    ScheduleRickGreenTable.getSpeakerValues = response.speaker;
                    ScheduleRickGreenTable.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
    },

    created:function(){
        this.getAllEventsGreenSpeakerReports();
        this.getSpeaker();

    },
    computed: {
        displayedData () {
            return this.paginate(this.getAllEventValues);
        }
    },
    watch: {
        getAllEventValues () {
            this.setPages();
        }
    },
    filters: {
        trimWords(value){
            return value.split(" ").splice(0,10).join(" ") + '...';
        }
    }

});
   window.manageEventsRequestReport = manageEventsRequestReport;



  var ManageEventsTable = new Vue({
    'el': '#ManageEventsTable',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            baseUrl: ''
        },
        paginationBlock:false,
        totalPages:0,
        perPage:20,
        searchValue:'',
        getAllEventValues:[],
        getPageList:[20,30,40,50],
        page: 1,
        homeUrl:' ',
        pages: [],
        getSpeakerValues:[],
        SpeakerId:'all'



    },


    methods: {


        postPageEntities:function (){
            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/perPageManageEventsShow?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    ManageEventsTable.getAllEventValues = response.speakerGreen;
                    ManageEventsTable.totalPages = response.totalReports;
                    window.ManageEventsTable.controls.loader = false;
                    window.ManageEventsTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

        searchgetAllManageEvents: function (){

            var $data=JSON.stringify({searchData: this.searchValue,perPage:this.perPage});
            this.controls.loader = true;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/searcheManageEvents?_wpnonce=' + settingObject.nonce;
            jQuery.post( $apiUrl,$data)
                .done(function (response) {
                    ManageEventsTable.getAllEventValues = response.speakerGreen;
                    ManageEventsTable.totalPages = response.totalReports;
                    window.ManageEventsTable.controls.loader = false;
                    window.ManageEventsTable.paginationBlock = true;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
        getAllManageEventsTableReports: function (){
            this.controls.loader = true;
            this.homeUrl=settingObject.homeUrl+'edit-event-information/?event_information_id=';
            var $apiUrl = settingObject.root + 'samadhan_events/v1/getScheduleManageEventsdata?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {
                    // console.log(response.allReports)

                    ManageEventsTable.getAllEventValues = response.speakerGreen;
                    ManageEventsTable.totalPages = response.totalReports;
                    window.ManageEventsTable.controls.loader = false;
                    window.ManageEventsTable.paginationBlock = true;

                })
                .fail(function (responsose) {
                })
                .always(function (response) {

                });

        },
        setPage: function(pageNumber) {
            this.page = pageNumber
        },
        setPages(){
            let numberOfPages = Math.ceil(this.getAllEventValues.length / this.perPage);
            this.totalPages=0;
            for (let index = 1; index <= numberOfPages; index++) {
                this.totalPages++;
                this.pages.push(index);


            }

        },
        paginate (getAllEvent){
            let page = this.page;
            let perPage = this.perPage;
            let from = (page * perPage) - perPage;
            let to = (page * perPage);
            return  getAllEvent.slice(from, to);
        },


    },

    created:function(){
        this.getAllManageEventsTableReports();


    },
    computed: {
        displayedData () {
            return this.paginate(this.getAllEventValues);
        }
    },
    watch: {
        getAllEventValues () {
            this.setPages();
        }
    },
    filters: {
        trimWords(value){
            return value.split(" ").splice(0,10).join(" ") + '...';
        }
    }

});
  window.ManageEventsTable = ManageEventsTable;


  var schedulingRequestForm = new Vue({
    'el': '#schedulingRequestForm',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            buttonDisable:true,
        },
        // notification: {
        //     name: '',
        //     eventType: '',
        //     setting: '',
        //     address: '',
        // },
        contacts: {
            ContactDate:new Date().toISOString().substr(0, 10),
            FirstRequest: '',
            Organization: '',
            Title: '',
            FirstName: '',
            LastName: '',
            Pastor: '',
            Address1: '',
            Address2: '',
            City: '',
            State: '',
            Zip: '',
            Phone1: '',
            Phone2: '',
            PhoneExt1: '',
            phoneExt2: '',
            Fax: '',
            Email: '',

        },
        leaders: {

            Title: '',
            FirstName: '',
            LastName: '',
            Pastor: '',
            Address1: '',
            Address2: '',
            City: '',
            State: '',
            Zip: '',
            Phone1: '',
            Phone2: '',
            Email: '',

        },
        eventsInfo: {
            Name: '',
            RedFlag: '',
            Type: '',
            Setting: '',
            Address1: '',
            Address2: '',
            City: '',
            State: '',
            Zip: '',
            Airport: '',
            TimeToAirport: '',
            StartDate: new Date().toISOString().substr(0, 10),
            StartHour: '',
            StartMinutes: '',
            StartTimeExt: '',
            EndDate: new Date().toISOString().substr(0, 10),
            EndHour: '',
            EndMinutes: '',
            EndTimeExt: '',

            AttendanceTime: '',
            AudienceTime: '',
            Speaker: '',
            AltSpeaker: '',
            Involvement: '',
            OtherParticipants: '',
            howTheyKnow: '',
            Notes: '',
            Deadline: new Date().toISOString().substr(0, 10),
            Decline: '',
            Reason: '',
        },
        getStatesValues:[],
        getSpeakerValues:[],
        setUpdateEventId:''

    },


    methods: {

        getEditEventDataByEventId:function (eventId){
             this.setUpdateEventId=eventId;
            var $apiUrl = settingObject.root + 'samadhan_events/v1/getEditEventDataByEventId?_wpnonce=' + settingObject.nonce;

            $jsonData = JSON.stringify({'EventId':eventId});


            var jqxhr = jQuery.post($apiUrl, $jsonData)
                .done(function (response) {

               console.log(response.getEventData[0]+ ' data ');

               var getData= jQuery.isEmptyObject(response.getEventData);
               if(!getData){



                   var self= window.schedulingRequestForm;


                    var ContactDate = new Date(response.getEventData[0].ContactDate).toJSON().slice(0,10);

                   var getStartDatde = new Date(response.getEventData[0].EventStartDate);

                   if(!isNaN(getStartDatde)) {
                       var StartDate = new Date(response.getEventData[0].EventStartDate).toJSON().slice(0, 10);

                       //var getStartDatde = new Date(response.getEventData[0].EventStartDate);
                       var startHour = getStartDatde.getHours();
                       var startMinites = getStartDatde.getMinutes();
                       var startHourAMPM = getStartDatde.getHours() >= 12 ? 'PM' : 'AM';
                   }else{
                       var startHour = '00';
                       var startMinites = '00';
                       var startHourAMPM = 'AM';
                   }

                   var getEndDate = new Date(response.getEventData[0].EventEndDate);

                   if(!isNaN(getEndDate)){
                    var EndDate = new Date(response.getEventData[0].EventEndDate).toJSON().slice(0,10);

                    //var getEndDate = new Date(response.getEventData[0].EventEndDate);
                    var endHour=getEndDate.getHours();
                    var endMinites=getEndDate.getMinutes();
                    var endHourAMPM=getEndDate.getHours() >= 12 ? 'PM' : 'AM';
                   }else{
                       var endHour='00';
                       var endMinites='00';
                       var endHourAMPM= 'AM';
                   }
                    var Deadline = new Date(response.getEventData[0].Deadline).toJSON().slice(0,10);


                    self.contacts.ContactDate=ContactDate;
                    self.contacts.FirstRequest=response.getEventData[0].FirstTime;
                    self.contacts.Organization=response.getEventData[0].Organization;
                    self.contacts.Title=response.getEventData[0].Title;
                    self.contacts.FirstName=response.getEventData[0].FirstName;
                    self.contacts.LastName=response.getEventData[0].LastName;
                    self.contacts.Pastor=response.getEventData[0].Pastor;
                    self.contacts.Address1=response.getEventData[0].Address1;
                    self.contacts.Address2=response.getEventData[0].Address2;
                    self.contacts.City=response.getEventData[0].City;
                    self.contacts.State=response.getEventData[0].State;
                    self.contacts.Zip=response.getEventData[0].Zip;
                    self.contacts.Phone1=response.getEventData[0].Phone1;
                    self.contacts.Phone2=response.getEventData[0].Phone2;
                    self.contacts.PhoneExt1=response.getEventData[0].Extension1;
                    self.contacts.phoneExt2=response.getEventData[0].Extension2;
                    self.contacts.Fax=response.getEventData[0].Fax;
                    self.contacts.Email=response.getEventData[0].Email;


                    self.leaders.Title=response.getEventData[0].LeaderTitle;
                    self.leaders.FirstName=response.getEventData[0].LeaderFirstName;
                    self.leaders.LastName=response.getEventData[0].LeaderLastName;
                    self.leaders.Pastor=response.getEventData[0].LeaderPastor;
                    self.leaders.Address1=response.getEventData[0].LeaderAddress1;
                    self.leaders.Address2=response.getEventData[0].LeaderAddress2;
                    self.leaders.City=response.getEventData[0].LeaderCity;
                    self.leaders.State=response.getEventData[0].LeaderState;
                    self.leaders.Zip=response.getEventData[0].LeaderZip;
                    self.leaders.Phone1=response.getEventData[0].LeaderPhone1;
                    self.leaders.Phone2=response.getEventData[0].LeaderPhone2;
                    self.leaders.Email=response.getEventData[0].LeaderEmail;


                    self.eventsInfo.Name=response.getEventData[0].Event;
                    self.eventsInfo.RedFlag=response.getEventData[0].RedFlagId;
                    self.eventsInfo.Type=response.getEventData[0].EventType;
                    self.eventsInfo.Setting=response.getEventData[0].Setting;
                    self.eventsInfo.Address1=response.getEventData[0].EventAddress1;
                    self.eventsInfo.Address2=response.getEventData[0].EventAddress2;
                    self.eventsInfo.City=response.getEventData[0].EventCity;
                    self.eventsInfo.State=response.getEventData[0].EventState;
                    self.eventsInfo.Zip=response.getEventData[0].EventZip;
                    self.eventsInfo.Airport=response.getEventData[0].Airport;
                    self.eventsInfo.TimeToAirport=response.getEventData[0].TimeToAirport;
                    self.eventsInfo.StartDate=StartDate;
                    self.eventsInfo.StartHour=startHour;
                    self.eventsInfo.StartMinutes=startMinites;
                    self.eventsInfo.StartTimeExt=startHourAMPM;
                    self.eventsInfo.EndDate=EndDate;
                    self.eventsInfo.EndHour=endHour;
                    self.eventsInfo.EndMinutes=endMinites;
                    self.eventsInfo.EndTimeExt=endHourAMPM;
                    self.eventsInfo.AttendanceTime=response.getEventData[0].Attendance;
                    self.eventsInfo.AudienceTime=response.getEventData[0].Audience;
                    self.eventsInfo.Speaker=response.getEventData[0].SpeakerRequestId;
                    self.eventsInfo.AltSpeaker=response.getEventData[0].AltSpeaker;
                    self.eventsInfo.Involvement=response.getEventData[0].Involvement;
                    self.eventsInfo.OtherParticipants=response.getEventData[0].OtherParticipants;
                    self.eventsInfo.howTheyKnow=response.getEventData[0].HowTheyKnow;
                    self.eventsInfo.Notes=response.getEventData[0].EventNotes;
                    self.eventsInfo.Deadline=Deadline;
                    self.eventsInfo.Decline=response.getEventData[0].Decline;
                    self.eventsInfo.Reason=response.getEventData[0].DeclineReasonId;


                    }



                   // schedulingRequestForm.getStatesValues = response.getEvent;
                    //schedulingRequestForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },
        saveScheduleRequistSubmitedButton: function () {

            this.controls.loader = true;
            this.controls.message ='';

            var _startDateTime= this.eventsInfo.StartDate+' '+this.eventsInfo.StartHour+':'+this.eventsInfo.StartMinutes+':'+this.eventsInfo.StartTimeExt;
            var _endDateTime= this.eventsInfo.EndDate+' '+this.eventsInfo.EndHour+':'+this.eventsInfo.EndMinutes+':'+this.eventsInfo.EndTimeExt;

           // console.log(_dateTime + ' Save ');


            var $apiUrl = settingObject.root + 'samadhan_schedule/v1/saveScheduleRequestForm?_wpnonce=' + settingObject.nonce;
            var $data = {
                updateEventId:this.setUpdateEventId,
                user: [''],
                events:[
                    {Event: this.eventsInfo.Name},
                    {Setting: this.eventsInfo.Setting},
                    {EventAddress1: this.eventsInfo.Address1},
                    {EventAddress2: this.eventsInfo.Address2},
                    {EventCity: this.eventsInfo.City},
                    {EventState: this.eventsInfo.State},
                    {EventZip: this.eventsInfo.Zip},
                    {LeaderTitle: this.leaders.Title},
                    {LeaderLastName: this.leaders.LastName},
                    {LeaderAddress1: this.leaders.Address1},
                    {LeaderAddress2: this.leaders.Address2},
                    {LeaderCity: this.leaders.City},
                    {LeaderState: this.leaders.State},
                    {LeaderZip: this.leaders.Zip},
                    {LeaderPhone1: this.leaders.Phone1},
                    {LeaderPhone2: this.leaders.Phone2},
                    {LeaderEmail: this.leaders.Email},
                    {Attendance: this.eventsInfo.AttendanceTime},
                    {Audience: this.eventsInfo.AudienceTime},
                    {OtherParticipants: this.eventsInfo.OtherParticipants},
                    {Airport: this.eventsInfo.Airport},
                    {EventNotes: this.eventsInfo.Notes},
                    {Scheduled: this.contacts.FirstRequest},
                    {EventStartDate: _startDateTime},
                    {EventEndDate: _endDateTime},
                    {TimeToAirport: this.eventsInfo.TimeToAirport},
                    {LeaderFirstName: this.leaders.FirstName},
                    {Involvement: this.eventsInfo.Involvement},
                    {EventType: this.eventsInfo.Type},
                    {LeaderPastor: this.leaders.Pastor},
                    {RedFlagId: this.eventsInfo.RedFlag},
                    {RedFlagNotes: ''},
                    {WillingToReturn: ''},
                    {FollowUpResponse: ''},
                    {FollowUpNotes: ''},
                ],
                request:[

                    {EventId: 0},
                    {Organization: this.contacts.Organization},
                    {Title: this.contacts.Title},
                    {FirstName: this.contacts.FirstName},
                    {LastName: this.contacts.LastName},
                    {Address1: this.contacts.Address1},
                    {Address2: this.contacts.Address2},
                    {City: this.contacts.City},
                    {State: this.contacts.State},
                    {Zip: this.contacts.Zip},
                    {Phone1: this.contacts.Phone1},
                    {Phone2: this.contacts.Phone2},
                    {Extension1: this.contacts.PhoneExt1},
                    {Extension2: this.contacts.phoneExt2},
                    {Fax: this.contacts.Fax},
                    {Email: this.contacts.Email},
                    {FirstTime: this.contacts.FirstRequest},
                    {SpeakerRequestId: this.eventsInfo.Speaker},
                    {Decline: this.eventsInfo.Decline},
                    {ContactDate: this.contacts.ContactDate},
                    {Deadline: this.eventsInfo.Deadline},
                    {Notes: this.eventsInfo.Notes},
                    {AltSpeaker: this.eventsInfo.AltSpeaker},
                    {HowTheyKnow: this.eventsInfo.howTheyKnow},
                    {Pastor: this.contacts.Pastor},
                    {DeclineReasonId: this.eventsInfo.Reason},
                ],
            };



            $jsonData = JSON.stringify($data);


            var jqxhr = jQuery.post($apiUrl, $jsonData)
                .done(function (response) {
                    window.schedulingRequestForm.controls.message = response.status;
                    window.schedulingRequestForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

        getStates: function (){
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {

                    schedulingRequestForm.getStatesValues = response.states;
                    schedulingRequestForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },
        getSpeaker: function (){
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_speaker_all_data?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {

                    schedulingRequestForm.getSpeakerValues = response.speaker;
                    schedulingRequestForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },

    },
    created:function(){
      var eventId=jQuery("#eventId").val();
      this.getEditEventDataByEventId(eventId);
      this.getStates();
      this.getSpeaker();
    },


});
  window.schedulingRequestForm = schedulingRequestForm;

  /******************End Schedule Section*****************/
/************************************************************/
/**************Start Donor Section*********************/


  var maintainManageDonorForm = new Vue({
    'el': '#maintainManageDonorForm',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            buttonDisable:true,
        },

        donor: {
            Id :0,
            NopID:0,
            FirstName:'',
            LastName :'',
            Company :'',
            Address1 :'',
            Address2 :'',
            City :'',
            State :'',
            Zip :'',
            Email :'',
            emailOption :'',
            activeStatus :'',
            totalDonationsCount :0,
            totalDonationAmount :0,
            currentLevel :0,
            totalDonationCtLY :0,
            totalDonationAmtLY :0,
            Phone :''
        },
        getStatesValues:[],

    },


    methods: {

        MajorDonorSubmitButton: function () {
            this.controls.loader = true;

            var $apiUrl = settingObject.root + 'samadhan_schedule/v1/saveMajorDonorSubmitButtonForm?_wpnonce=' + settingObject.nonce;
            var $data = {
                    Id :this.donor.Id,
                    NopID:this.donor.NopID,
                    FirstName:this.donor.FirstName,
                    LastName :this.donor.LastName,
                    Company :this.donor.Company,
                    Address1 :this.donor.Address1,
                    Address2 :this.donor.Address2,
                    City :this.donor.City,
                    State :this.donor.State,
                    Zip :this.donor.Zip,
                    Email :this.donor.Email,
                    emailOption :this.donor.emailOption,
                    activeStatus :this.donor.activeStatus,
                    totalDonationsCount :this.donor.totalDonationsCount,
                    totalDonationAmount :this.donor.totalDonationAmount,
                    currentLevel :this.donor.currentLevel,
                    totalDonationCtLY :this.donor.totalDonationCtLY,
                    totalDonationAmtLY :this.donor.totalDonationAmtLY,
                    Phone :this.donor.Phone
            };

            $jsonData = JSON.stringify($data);


            var jqxhr = jQuery.post($apiUrl, $jsonData)
                .done(function (response) {
                    window.maintainManageDonorForm.controls.message = response.status;
                    window.maintainManageDonorForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });
        },

        getStates: function (){
            var $apiUrl = settingObject.root + 'samadhan_events/v1/get_country_states?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {

                    maintainManageDonorForm.getStatesValues = response.states;
                    maintainManageDonorForm.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },


    },
    created:function(){
        this.getStates();

    },


});
  window.maintainManageDonorForm = maintainManageDonorForm;

  var maintainManageDonorReports = new Vue({
    'el': '#maintainManageDonorReports',
    data: {
        controls: {
            loaded: false,
            message: '',
            notification: '',
            buttonDisable:true,
        },

        donor: {
            Id :0,
            NopID:0,
            FirstName:'',
            LastName :'',
            Company :'',
            Address1 :'',
            Address2 :'',
            City :'',
            State :'',
            Zip :'',
            Email :'',
            emailOption :'',
            activeStatus :'',
            totalDonationsCount :0,
            totalDonationAmount :0,
            currentLevel :0,
            totalDonationCtLY :0,
            totalDonationAmtLY :0,
            Phone :''
        },
        getStatesValues:[],

    },


    methods: {



        getDonorList: function (){
            var $apiUrl = settingObject.root + 'samadhan_schedule/v1/getDonorReportsData?_wpnonce=' + settingObject.nonce;
            var jqxhr = jQuery.get($apiUrl)
                .done(function (response) {

                    maintainManageDonorReports.getStatesValues = response.results;
                    maintainManageDonorReports.controls.loader = false;
                })
                .fail(function (response) {
                })
                .always(function (response) {

                });

        },


    },
    created:function(){
        //this.getDonorList();

    },


});
  window.maintainManageDonorReports = maintainManageDonorReports;


