<?php


class SMDN_Rest_API_Connect
{

    function __construct()
    {
        add_action('rest_api_init',array($this,'samadhan_contact_rest_api_init'));

    }

    function samadhan_contact_rest_api_init()
    {



        /************ Get Function***************/
        register_rest_route( 'samadhan_events/v1', '/eventRequstFilter', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::samadhan_get_all_manage_events_request_data'
        ) );
        register_rest_route( 'samadhan_events/v1', '/get_country_states', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'EventFunctons::samadhan_get_country_states'
        ) );
        register_rest_route( 'samadhan_events/v1', '/get_all_event_request', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'EventFunctons::samadhan_get_all_manage_events_request_data'
        ) );


        /************ Set Function***************/
        register_rest_route( 'samadhan_events/v1', '/save_events', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'ContactForm::save_contact'
        ) );
        register_rest_route( 'samadhan_events/v1', '/save_eventsInformation', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::save_eventsInformation_data'
        ) );
        register_rest_route( 'samadhan_events/v1', '/save_contactInformation', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::save_contactsInformation_data'
        ) );
        register_rest_route( 'samadhan_events/v1', '/save_leadersInformation', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::save_leadersInformation_data'
        ) );

        register_rest_route( 'samadhan_events/v1', '/searchEventValue', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::samadhan_get_all_manage_events_request_data'
        ) );
        register_rest_route( 'samadhan_events/v1', '/perPageEventShow', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::samadhan_get_all_manage_events_request_data'
        ) );

        /********************************************/
        /************** Schedule*********************/
        /********************************************/

        /************ Start Teacher form ***************/
        register_rest_route( 'samadhan_schedule/v1', '/saveAddSpeakerForm', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveAddSpeakerFormData'
        ) );

            register_rest_route( 'samadhan_schedule/v1', '/getSpeakerDataBySpeakerId', array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => 'EventFunctons::getSpeakerDataBySpeakerId'
            ) );
        /************ End Teacher form ***************/
        /************ Start Teacher list Table ***************/
        register_rest_route( 'samadhan_events/v1', '/perPageTeacherShow', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::get_speaker_all_data'
        ) );
        register_rest_route( 'samadhan_events/v1', '/searchTeacherValue', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::get_speaker_all_data'
        ) );
      register_rest_route( 'samadhan_events/v1', '/get_speaker_all_data', array(
          'methods'  => WP_REST_Server::READABLE,
          'callback' => 'EventFunctons::get_speaker_all_data'
      ) );
        /************ End Teacher list Table ***************/


        /**************Start Schedule Events Speaker Green*****/
        register_rest_route( 'samadhan_events/v1', '/getEditEventDataByEventId', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getEditEventDataById'
        ) );
        register_rest_route( 'samadhan_events/v1', '/getEventDataById', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getEventDataById'
        ) );

         register_rest_route( 'samadhan_events/v1', '/EventsFilterspeaker', array(
             'methods'  => WP_REST_Server::CREATABLE,
             'callback' => 'EventFunctons::getScheduleRickGreenTable'
         ) );
        register_rest_route( 'samadhan_events/v1', '/perPageEvents_green_speakerShow', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getScheduleRickGreenTable'
        ) );
        register_rest_route( 'samadhan_events/v1', '/searchevEnts_green_speakerValue', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getScheduleRickGreenTable'
        ) );
        register_rest_route( 'samadhan_events/v1', '/get_schedule_events_green_speaker_data', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'EventFunctons::getScheduleRickGreenTable'
        ) );

        /**************End Schedule Events Speaker Green*****/


        /**************Start Schedule Manage Events *****/

        register_rest_route( 'samadhan_events/v1', '/perPageManageEventsShow', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getScheduleRickGreenTable'
        ) );
        register_rest_route( 'samadhan_events/v1', '/searcheManageEvents', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getScheduleRickGreenTable'
        ) );
        register_rest_route( 'samadhan_events/v1', '/getScheduleManageEventsdata', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'EventFunctons::getScheduleRickGreenTable'
        ) );

        /**************End Schedule Manage Events*****/





        register_rest_route( 'samadhan_events/v1', '/mediaRequstFilter', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::get_media_request_report'
        ) );
        register_rest_route( 'samadhan_events/v1', '/perPageMediaRequestShow', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::get_media_request_report'
        ) );
        register_rest_route( 'samadhan_events/v1', '/searchMediaRequestValue', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::get_media_request_report'
        ) );
      register_rest_route( 'samadhan_events/v1', '/get_media_request_report', array(
          'methods'  => WP_REST_Server::READABLE,
          'callback' => 'EventFunctons::get_media_request_report'
      ) );


    /************ Set Function***************/
        register_rest_route( 'samadhan_schedule/v1', '/saveMediaRequestForm', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveMediaRequestFormData'
        ) );


        register_rest_route( 'samadhan_schedule/v1', '/getMediaRequestByData', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::getMediaRequestDataByRequestID'
        ) );



        register_rest_route( 'samadhan_schedule/v1', '/saveCreateItineraryForm', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveCreateItineraryFormData'
        ) );

        register_rest_route( 'samadhan_schedule/v1', '/saveScheduleRequestForm', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveScheduleRequestFormData'
        ) );


        /********************************************/
        /************** Donor*********************/
        /********************************************/


        /************ Set Function***************/

        register_rest_route( 'samadhan_schedule/v1', '/getDonorReportsData', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'EventFunctons::getMajorDonorReports'
        ) );
        register_rest_route( 'samadhan_schedule/v1', '/saveMajorDonorSubmitButtonForm', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveMajorDonorSubmitButtonForm'
        ) );


        register_rest_route( 'samadhan_major_donation/v1', '/saveMajorDoner', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveMajorDoner'
        ) );
        register_rest_route( 'samadhan_major_donation/v1', '/saveMajorDonationHistory', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveMajorDonationHistory'
        ) );

        register_rest_route( 'samadhan_major_donation/v1', '/saveMajorDonorNotes', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'EventFunctons::saveMajorDonorNotes'
        ) );

    }


}
new SMDN_Rest_API_Connect();