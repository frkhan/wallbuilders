<?php


class SHcCreateTable
{
    public  function __construct()
    {

    }
    public static function on_create_table(){
        self::sch_events_table_create_after_install();
        self::sch_itinerary_table_create_after_install();
        self::sch_itinerary_details_table_create_after_install();
        self::sch_reguest_table_create_after_install();
        self::sch_schedule_table_create_after_install();
        self::sch_decline_reason_table_create_after_install();
        self::sch_media_request_table_create_after_install();
        self::sch_red_flag_table_create_after_install();
        self::sch_speaker_table_create_after_install();
        self::sch_topic_table_create_after_install();


        self::MajorDonor_table_create_after_install();
        self::MajorDonorHistory_table_create_after_install();
        self::MajorDonorNotes_table_create_after_install();

    }
    public static function on_remove_table(){
        self::sch_events_table_remove_after_uninstall();
        self::sch_itinerary_table_remove_after_uninstall();
        self::sch_itinerary_details_table_remove_after_uninstall();
        self::sch_reguest_table_remove_after_uninstall();
        self::sch_schedule_table_remove_after_uninstall();
        self::sch_decline_table_remove_after_uninstall();
        self::sch_media_request_table_remove_after_uninstall();
        self::sch_red_flag_table_remove_after_uninstall();
        self::sch_speaker_table_remove_after_uninstall();
        self::sch_topic_table_remove_after_uninstall();


        self::MajorDonor_table_remove_after_uninstall();
        self::MajorDonorHistory_table_remove_after_uninstall();
        self::MajorDonorNotes_table_remove_after_uninstall();

    }

    /**************activation plugin create  table*************/

    public static function sch_itinerary_table_create_after_install(){

        global $wpdb;

        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_Itinerary';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                   Id bigint  NOT NULL auto_increment primary key,
                    SpeakerId int NOT NULL,
                    ItineraryDate datetime NOT NULL,
                    IntroText varchar(3000) NULL,
                    ConcludingText varchar(3000) NULL,
                    CreateDate datetime NULL,
                    CreatedBy varchar(50) NULL,
                    ModifyDate datetime NULL,
                    ModifiedBy varchar(50) NULL,
                    WeatherCity varchar(50) NULL,
                    WeatherState varchar(50) NULL
        )";
            $wpdb->query($sql);

            add_option( 'sch_itinerary_db_version', $test_db_version );




    }
    public static function sch_itinerary_details_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_ItineraryDetails';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                Id bigint  NOT NULL auto_increment primary key,
                ItineraryId bigint NOT NULL,
                DetailType varchar(50) NOT NULL,
                Title varchar(250) NULL,
                TextA varchar(3000) NULL,
                TextB varchar(3000) NULL,
                TextC varchar(3000) NULL,
                TextD varchar(3000) NULL,
                Sequence int NOT NULL,
                CreateDate datetime NULL,
                CreatedBy varchar(50) NULL,
                ModifyDate datetime NULL,
                ModifiedBy varchar(50) NULL
        ) ";

             $wpdb->query($sql);
            add_option( 'sch_itinerary_details_db_version', $test_db_version );



    }
    public static function sch_reguest_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_Request';  // table name


            $sql = "CREATE TABLE {$db_table_name} (
        Id int(11)  NOT NULL auto_increment primary key,
        EventId int NOT NULL,
        Organization varchar(250) NULL,
        Title varchar(100) NULL,
        FirstName varchar(50) NULL,
        LastName varchar(50) NULL,
        Address1 varchar(100) NULL,
        Address2 varchar(100) NULL,
        City varchar(50) NULL,
        State varchar(50) NULL,
        Zip varchar(50) NULL,
        Phone1 varchar(50) NULL,
        Phone2 varchar(50) NULL,
        Extension1 varchar(10) NULL,
        Extension2 varchar(10) NULL,
        Fax varchar(20) NULL,
        Email varchar(100) NULL,
        FirstTime bit NOT NULL,
        SpeakerRequestId int NOT NULL,
        Decline bit NOT NULL,
        ContactDate datetime NULL,
        Deadline datetime NULL,
        Notes varchar(3000) NULL,
        AltSpeaker bit NOT NULL,
        HowTheyKnow varchar(500) NULL,
        Pastor bit NOT NULL,
        DeclineReasonId int NULL
        ) ";
      $wpdb->query($sql);

      add_option( 'sch_request_db_version', $test_db_version );



    }
    public static function sch_schedule_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_Schedule';  // table name


            $sql = "CREATE TABLE {$db_table_name} (
                Id int  NOT NULL auto_increment primary key,
                SpeakerId int NOT NULL,
                EventId int NOT NULL,
                CreateDate datetime NULL,
                CreatedBy varchar(50) NULL
        )";

            $wpdb->query($sql);
            add_option( 'sch_schedule_db_version', $test_db_version );



    }
    public static function sch_decline_reason_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_DeclineReason';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                Id int(11)   NOT NULL auto_increment primary key ,
                Reason  varchar(50)  NULL
        ) ";

        $wpdb->query($sql);
            add_option( 'sch_declineReason_db_version', $test_db_version );

    }


    public static function sch_media_request_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_MediaRequest';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                Id int(11)   NOT NULL auto_increment primary key ,
                ShowName  varchar(100)  NULL,
                HostName varchar(100)  NULL,
                Topic varchar(200)  NULL,
                ShowType varchar(200)  NULL,
                InterviewDateTime varchar(200)  NULL,
                TimeZone varchar(100)  NULL,
                ContactName varchar(200)  NULL,
                Email varchar(200)  NULL,
                InterviewType varchar(200)  NULL,
                LiveAudience varchar(200)  NULL,
                Notes varchar(200)  NULL,
                DateCreated varchar(200)  NULL,
                SpeakerId varchar(200)  NULL,
                InterviewLength varchar(200)  NULL,
                Phone varchar(200)  NULL,
                MediaType varchar(200)  NULL,
                Station varchar(200)  NULL,
                Active varchar(200)  NULL,
                Phone2 varchar(200)  NULL,
                Phone3 varchar(200)  NULL,
                Address varchar(200)  NULL,
                City varchar(200)  NULL,
                State varchar(200)  NULL,
                Zip varchar(200)  NULL,
                RedFlagId varchar(200)  NULL,
                RedFlagNotes varchar(200)  NULL
              
        )";

        $wpdb->query($sql);
            add_option( 'sch_mediaRequest_db_version', $test_db_version );


    }

    public static function sch_red_flag_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_RedFlag';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                 Id int(11)  NOT NULL auto_increment primary key ,
                RedFlagType  varchar(100)  NULL,
               
        ) ";
        $wpdb->query($sql);
            add_option( 'sch_redflag_db_version', $test_db_version );


    }
    public static function sch_speaker_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_Speaker';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                Id int  NOT NULL auto_increment primary key,
                Nickname varchar(50) NULL,
                FirstName varchar(50) NULL,
                LastName varchar(50) NULL,
                FullName varchar(100) NULL
        ) ";

        $wpdb->query($sql);
            add_option( 'sch_speaker_db_version', $test_db_version );


    }
    public static function sch_topic_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_Topic';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                 Id int  NOT NULL auto_increment primary key,
                 EventId int NOT NULL,
                 Topic varchar(50) NOT NULL,
                 Description varchar(3000) NULL
        ) ";

        $wpdb->query($sql);
            add_option( 'sch_topic_db_version', $test_db_version );

    }
    public static function sch_events_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'SCH_Event';  // table name

            $sql = "CREATE TABLE {$db_table_name} (
                            Id int   NOT NULL auto_increment primary key,
                            RequestId int NOT NULL,
                            Event varchar(250) NULL,
                            Setting varchar(250) NULL,
                            EventAddress1 varchar(100) NULL,
                            EventAddress2 varchar(100) NULL,
                            EventCity varchar(50) NULL,
                            EventState varchar(50) NULL,
                            EventZip varchar(50) NULL,
                            LeaderTitle varchar(100) NULL,
                            LeaderLastName varchar(50) NULL,
                            LeaderAddress1 varchar(100) NULL,
                            LeaderAddress2 varchar(100) NULL,
                            LeaderCity varchar(50) NULL,
                            LeaderState varchar(50) NULL,
                            LeaderZip varchar(50) NULL,
                            LeaderPhone1 varchar(50) NULL,
                            LeaderPhone2 varchar(50) NULL,
                            LeaderEmail varchar(100) NULL,
                            Attendance varchar(250) NULL,
                            Audience varchar(250) NULL,
                            OtherParticipants varchar(250) NULL,
                            Airport varchar(250) NULL,
                            EventNotes varchar(3000) NULL,
                            Scheduled bit NOT NULL,
                            EventStartDate datetime NULL,
                            EventEndDate datetime NULL,
                            TimeToAirport varchar(100) NULL,
                            LeaderFirstName varchar(300) NULL,
                            Involvement varchar(500) NULL,
                            EventType varchar(100) NULL,
                            LeaderPastor bit NOT NULL,
                            RedFlagId int NULL,
                            RedFlagNotes varchar(3000) NULL,
                            WillingToReturn bit NOT NULL,
                            FollowUpResponse varchar(3000) NULL,
                            FollowUpNotes varchar(3000) NULL
        ) ";







         $wpdb->query($sql);
         add_option( 'sch_events_db_version', $test_db_version );

    }

    public static function MajorDonor_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'MajorDonor';  // table name

        $sql = "CREATE TABLE {$db_table_name} (
                        Id int  NOT NULL auto_increment primary key,
                        WooId int NULL,
                        ConnectId int NULL,
                        NopID int NULL,
                        FirstName varchar(50) NULL,
                        LastName varchar(50) NULL,
                        Company varchar(50) NULL,
                        Address1 varchar(100) NULL,
                        Address2 varchar(100) NULL,
                        City varchar(50) NULL,
                        State varchar(50) NULL,
                        Zip varchar(50) NULL,
                        Email varchar(100) NULL,
                        emailOption varchar(30) NULL,
                        activeStatus bit NOT NULL,
                        totalDonationsCount int NULL,
                        totalDonationAmount decimal(18, 2) NULL,
                        currentLevel int NULL,
                        totalDonationCtLY int NULL,
                        totalDonationAmtLY decimal(18, 2) NULL,
                        Phone varchar(15) NULL
        ) ";

        $wpdb->query($sql);
        add_option( 'MajorDonor_db_version', $test_db_version );

    }
    public static function MajorDonorHistory_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'MajorDonorHistory';  // table name

        $sql = "CREATE TABLE {$db_table_name} (
                            Id int  NOT NULL auto_increment primary key,
                            WooId int NULL,
                            ConnectID int NULL,
                            MajorDonorID int NULL,
                            Source varchar(50) NULL,
                            OrderNumber varchar(30) NULL,
                            OriginalCreateDate datetime NULL,
                            ProductName varchar(50) NULL,
                            Quantity int NULL,
                            UnitPrice decimal(18, 2) NULL,
                            TotalAmount decimal(18, 2) NULL,
                            FirstName varchar(50) NULL,
                            LastName varchar(50) NULL,
                            Company varchar(50) NULL,
                            Address1 varchar(60) NULL,
                            Address2 varchar(60) NULL,
                            City varchar(40) NULL,
                            State varchar(40) NULL,
                            Zip varchar(20) NULL,
                            Email varchar(100) NULL,
                            Phone varchar(20) NULL,
                            OriginalClientId bigint NULL) ";

        $wpdb->query($sql);
        add_option( 'MajorDonorHistory_db_version', $test_db_version );

    }
    public static function MajorDonorNotes_table_create_after_install(){

        global $wpdb;
        $test_db_version=1.01;
        $db_table_name = $wpdb->prefix .'MajorDonorNotes';  // table name

        $sql = "CREATE TABLE {$db_table_name}(
                        Id int  NOT NULL auto_increment primary key,
                        WooId int NULL,
                        MajorDonorId int NULL,
                        NopId int NULL,
                        CreateDate datetime NULL,
                        CreatedBy varchar(50) NULL,
                        ModifyDate datetime NULL,
                        ModifiedBy varchar(50) NULL,
                        Note varchar(3000) NULL)";

        $wpdb->query($sql);
        add_option( 'MajorDonorNotes_db_version', $test_db_version );

    }

    /**************unistall remove table*************/
    public static function sch_events_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_Event';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_events_db_version");


    }
    public static function sch_itinerary_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_Itinerary';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_itinerary_db_version");


    }
    public static function sch_itinerary_details_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_ItineraryDetails';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_itinerary_details_db_version");


    }
    public static function sch_reguest_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_Request';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_request_db_version");


    }
    public static function sch_schedule_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_Schedule';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_schedule_db_version");


    }
    public static function sch_decline_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_DeclineReason ';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_declineReason_db_version");


    }
    public static function sch_media_request_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_MediaRequest';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_mediaRequest_db_version");


    }
    public static function sch_red_flag_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_RedFlag';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_redflag_db_version");


    }
    public static function sch_speaker_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_Speaker';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_speaker_db_version");


    }
    public static function sch_topic_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'SCH_Topic';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("sch_topic_db_version");


    }


    public static function MajorDonor_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix . 'MajorDonor';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("MajorDonor_db_version");


    }
    public static function MajorDonorHistory_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix .'MajorDonorHistory';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("MajorDonorHistory_db_version");


    }
    public static function MajorDonorNotes_table_remove_after_uninstall(){


        global $wpdb;
        $table_name = $wpdb->prefix .'MajorDonorNotes';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option("MajorDonorNotes_db_version");


    }
}
new SHcCreateTable();