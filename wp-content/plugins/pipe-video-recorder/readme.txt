=== Pipe Video Recorder ===
Contributors: lucian.alexandru, naicuoctavian, bugnariu.virgil
Donate link: https://addpipe.com/
Tags: video recording,video recorder,record video,vlog,record,wordpress video,webcam,camera,shortcode,clip,av,admin,comments,crowdsourced video,user generated content,video posts,video interviews,video reviews,video submission,video testimonial,video upload,user generated video,cam,recorder,recording,insert,page,video comments,video widget,video interview plugin,video submission plugin,video testimonial plugin,Post,posts,sidebar,social,media,users,video,gravity,gravity forms,ninja,ninja forms
Requires at least: 3.1
Tested up to: 4.9
Stable tag: 1.5.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Record video posts and accept video submissions on your mobile and desktop website using the new Pipe Video Recorder Plugin for WordPress.

== Description ==

The **Pipe Video Recorder Plugin** handles the integration between the [Pipe Video Recorder](https://addpipe.com) platform and WordPress.

**Capture user generated video content** including video feedback, video submissions, video messages, and video testimonials straight form your WordPress website.

**Safely accept video resumes for e-recruiting** from any post/page on your website. The easiest way for online recruiters to collect video resumes.

**Transform your blog into a VLOG** using the backend video recording feature. Review your videos and post only those takes where you nailed it.

**Save time and money** since there's no more need for an expensive media server and everything happens automatically behind the scenes.

**Review and download recorded videos** straight from your WordPress backend.

**Embed recorded videos** in any web page or blog post (not necessarily on your website).

**Easily add crowdsourced videos to your website** and skyrocket your user engagement. You'll immediately start seeing the results.

###Features:###

* Record from desktop and mobile - Pipe makes sure your website users can record video no matter the device and browser they're using.
* Up to 4k HD Video - Pipe can record and properly manage videos up to 4k in resolution.
* Play everywhere - different devices record to different video file formats including .mov, .mp4, .3gp and .flv. We make sure the final video is a proper .mp4 file that can be played on any device.
* Manage videos from both your WordPress backend and the Pipe account area on https://addpipe.com
* Bandwidth independent - Pipe's client side and server side buffering make it possible to record high quality videos over slow or unstable connections like 3G, 4G and public Wi-Fi.
* Snapshots are automatically created for each video
* Works with any WordPress template


###A note on video file formats:###

The [Pipe Video Recorder](https://addpipe.com) platform handles the video recording and playback process for both the web and mobile ensuring all the different video formats are converted to .mp4 for ease of delivery as well as providing secure storage & delivery through the Amazon CDN.


###Compatible with the most popular forms plugins:###

* For **Gravity Forms** see [How to Record Video Using Gravity Forms and Pipe](https://addpipe.com/blog/record-video-with-gravity-forms-and-pipe-video-recorder/).
* For **Ninja Forms** see [How to record videos on your WordPress website using Ninja Forms and Pipe](https://addpipe.com/blog/how-to-add-video-recording-to-your-ninja-forms/).

== Installation ==

**Installing and setting up the plugin**

1. In your WordPress backend (admin menu), go to `Plugins` -> `Add New` and search for `Pipe Video Recorder`
2. Click `Install`, then `Activate`.
3. A new menu will new be available in the left sidebar `Pipe Video Recorder`. Click on it and go to `Set-up`
4. Enter your addpipe.com **Account Hash** and click `[Save]`. It can be found in your https://addpipe.com account by clicking on the top right **Account** link.
5. Go to your addpipe.com account and under Webhooks > Create New Webhook enter the URL of your WordPress website, check the `video_converted` AND `video_copied_pipe_s3` events and click `Save webhook`.

From now on your newly recorded videos will now show up in the WordPress backend.

**Recording your first video**

1. In your WordPress backend (admin menu), go to  `Pipe Video Recorder`->`Record Video`
2. Record a video
3. Go to  `Pipe Video Recorder`->`Recorded Videos` to view, download or embed it

**Embedding a video recorder in a blog post or page**

1. In your WordPress backend (admin menu), go to `Plugins` -> `Embed Video Recorder`
2. Generate a shortcode using the desired video resolution and maximum length
3. Use the shortcode in a blog post or page
4. All recorded videos will show up in the `Recorded Videos` section of the plugin

== Screenshots ==

1. Plugin settings
2. Record a new video from the WP backend
3. View all recorded videos and play, download or embed them
4. Generating short codes for embedding the video recorder
5. Embedding the video recorder in a blog post using one of the generated short codes
6. Video recorder embedded in frontend (members and visitors can record themselves)

== Changelog ==

= 1.0.0 =
* First release

= 1.0.1 =
* Small bugfixes

= 1.0.2 =
* Menu creation patch and security enhancements

= 1.0.3 =
* Plugin now only takes into consideration the `video_transcoded` webhook

= 1.5 = 
* Backend interface improvements

= 1.5.1 = 
* Fixed issue with downloading videos not working

= 1.5.2 = 
* Download function will work with all of Pipe's future regional S3 storage locations

= 1.5.3 = 
* Updated plugin to work with the new `video_converted` and `video_copied_pipe_s3` webhooks
* Updated copy and fixed a misfiring internal link to the list of videos

= 1.5.4 = 
* Fixed issue with downloading videos from our new EU/US S3 buckets not working resulting in 403 Forbidden

= 1.5.5 = 
* Added 5 capabilities which can be controlled for each user role using a user role editor. There's an umbrella capability that's needed for any other (pipe_access_plugin) and 4 granular capabilities, one for each menu item (pipe_access_record,pipe_access_embed, pipe_access_recordings, pipe_access_setup)