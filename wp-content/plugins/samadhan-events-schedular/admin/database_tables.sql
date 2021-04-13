
/******************************Schedule Table Create *******************************************/


 /***********Sch_events************/



                    CREATE TABLE wp_SCH_Event(
                            Id int   NOT NULL auto_increment primary key,
                            RequestId int NOT NULL,
                            Event varchar(250) NULL,
                            Setting varchar(250) NULL,
                            Address1 varchar(100) NULL,
                            Address2 varchar(100) NULL,
                            City varchar(50) NULL,
                            State varchar(50) NULL,
                            Zip varchar(50) NULL,
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
                            Notes varchar(3000) NULL,
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
                            );


/*********** Sch_Request************/

            CREATE TABLE wp_SCH_Request(
                    Id int  NOT NULL auto_increment primary key,
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
                    );



   /***********Sch_Itinerary************/



                CREATE TABLE wp_SCH_Itinerary(
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
                    );


   /***********Sch_Itinerary_Details************/


            CREATE TABLE wp_SCH_ItineraryDetails(
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
                );







   /***********Sch_Schedule************/


CREATE TABLE wp_SCH_Schedule(
                Id int  NOT NULL auto_increment primary key,
                SpeakerId int NOT NULL,
                EventId int NOT NULL,
                CreateDate datetime NULL,
                CreatedBy varchar(50) NULL);


   /***********Sch_DeclineReason************/

    CREATE TABLE wp_SCH_DeclineReason (
                Id int(11)  NULL auto_increment primary key ,
                Reason  varchar(50)  NULL
                );




/*********** Sch_MediaRequest************/


        CREATE TABLE wp_SCH_MediaRequest(
                    Id int  NOT NULL auto_increment primary key,
                    ShowName varchar(100) NULL,
                    HostName varchar(100) NULL,
                    Topic varchar(250) NULL,
                    ShowType varchar(20) NULL,
                    InterviewDateTime datetime NOT NULL,
                    TimeZone varchar(10) NULL,
                    ContactName varchar(250) NULL,
                    Email varchar(100) NULL,
                    InterviewType varchar(20) NULL,
                    LiveAudience bit NOT NULL,
                    Notes varchar(3000) NULL,
                    DateCreated datetime NOT NULL,
                    SpeakerId int NOT NULL,
                    InterviewLength varchar(50) NULL,
                    Phone varchar(50) NULL,
                    MediaType varchar(250) NULL,
                    Station varchar(250) NULL,
                    Active bit NOT NULL,
                    Phone2 varchar(50) NULL,
                    Phone3 varchar(50) NULL,
                    Address varchar(250) NULL,
                    City varchar(50) NULL,
                    State varchar(50) NULL,
                    Zip varchar(50) NULL,
                    RedFlagId int NULL,
                    RedFlagNotes varchar(3000) NULL
                    );


   /***********Sch_RedFlag************/

    CREATE TABLE wp_SCH_RedFlag (
                Id int  NOT NULL auto_increment primary key,
                RedFlagType  varchar(50)  NULL
                );



 /***********Sch_Speaker************/

    CREATE TABLE wp_SCH_Speaker(
                Id int  NOT NULL auto_increment primary key,
                Nickname varchar(50) NULL,
                FirstName varchar(50) NULL,
                LastName varchar(50) NULL,
                FullName varchar(100) NULL
                );


      /***********Sch_Topic************/

     CREATE TABLE wp_SCH_Topic(
                 Id int  NOT NULL auto_increment primary key,
                 EventId int NOT NULL,
                 Topic varchar(50) NOT NULL,
                 Description varchar(3000) NULL
                 );




/******************************Donor Table Create *******************************************/


 /***********MajorDonor************/

            CREATE TABLE wp_MajorDonor(
                        Id int  NOT NULL auto_increment primary key,
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
                        Phone varchar(15) NULL);


/***********MajorDonorAuthTransaction************/
            CREATE TABLE wp_MajorDonorAuthTransaction(
                        Id int  NOT NULL auto_increment primary key,
                        MajorDonorID int NULL,
                        authTransId varchar(50) NULL,
                        authCode varchar(50) NULL,
                        settleDate datetime NULL,
                        firstName varchar(50) NULL,
                        lastName varchar(50) NULL,
                        company varchar(50) NULL,
                        address varchar(60) NULL,
                        city varchar(40) NULL,
                        state varchar(40) NULL,
                        zip varchar(20) NULL,
                        email varchar(100) NULL,
                        amount decimal(18, 2) NULL,
                        donationCode varchar(30) NULL,
                        exception bit NOT NULL,
                        Phone varchar(15) NULL);

/***********MajorDonorHistory************/
                CREATE TABLE wp_MajorDonorHistory(
                            Id int  NOT NULL auto_increment primary key,
                            WooId int NULL,
                            ConnectID int Null,
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
                            OriginalClientId bigint NULL);


/***************MajorDonorLevel*****************/
                CREATE TABLE wp_MajorDonorLevel(
                        Id int  NOT NULL auto_increment primary key,
                        Name varchar(50) NOT NULL,
                        MinPerPeriod decimal(18, 2) NOT NULL,
                        MaxPerPeriod decimal(18, 2) NOT NULL,
                        currentCount int NULL);
/****************MajorDonorLevelAchieved*************/
                    CREATE TABLE wp_MajorDonorLevelAchieved(
                        Id int  NOT NULL auto_increment primary key,
                        MajorDonorID int NOT NULL,
                        MajorDonorLevelID int NOT NULL,
                        DateAchieved datetime NOT NULL);
/************MajorDonorNopTransaction************/
                    CREATE TABLE wp_MajorDonorNopTransaction(
                        Id int  NOT NULL auto_increment primary key,
                        MajorDonorID int NULL,
                        nopCustomerId int NULL,
                        orderId int NULL,
                        paidDate datetime NULL,
                        amount decimal(18, 2) NULL);
/*************MajorDonorNotes***************/
                CREATE TABLE wp_MajorDonorNotes(
                        Id int  NOT NULL auto_increment primary key,
                        MajorDonorId int NULL,
                        CreateDate datetime NULL,
                        CreatedBy varchar(50) NULL,
                        ModifyDate datetime NULL,
                        ModifiedBy varchar(50) NULL,
                        Note varchar(3000) NULL);