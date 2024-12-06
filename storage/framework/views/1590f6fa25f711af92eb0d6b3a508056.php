<link rel="stylesheet" href="<?php echo e(asset('public/frontend/agora/index.css')); ?>">

<?php $__env->startSection('content'); ?>
    <?php if(astroauthcheck()): ?>
        <?php
            $userId = $callrequest->userId;
            $astrologerId = astroauthcheck()['astrologerId'];
            $callId = request()->query('callId');
            $call_type = request()->query('call_type');
        ?>
    <?php endif; ?>

    <div class="pt-1 pb-1 bg-red d-none d-md-block astroway-breadcrumb">
        <div class="container">
            <div class="row afterLoginDisplay">
                <div class="col-md-12 d-flex align-items-center">

                    <span style="text-transform: capitalize; ">
                        <span class="text-white breadcrumbs">
                            <a href="<?php echo e(route('front.astrologerindex')); ?>" style="color:white;text-decoration:none">
                                <i class="fa fa-home font-18"></i>
                            </a>
                            <i class="fa fa-chevron-right"></i> <span class="breadcrumbtext">Call</span>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>



    <input id="appid" type="hidden" placeholder="enter appid" value="<?php echo e($agoraAppIdValue->value); ?>">
    <input id="token" type="hidden" placeholder="enter token" value="<?php echo e($callrequest->token); ?>">
    <input id="channel" type="hidden" placeholder="enter channel name" value="<?php echo e($callrequest->channelName); ?>">

    <section class="container">
        <div class=" row">
            <div class="col-md-2 col-sm-12 order-md-0 order-2 bottom-sm-0 bottom-buttons">
                <div class="navigation flex-sm-column h-100">
                    <span id="remainingTime" class="color-red"><?php echo e($callrequest->call_duration); ?></span>
                    <button class="video-action-button mic" onclick="toggleMic()" id="mic-icon">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <?php if($call_type==11): ?>
                    <button class="video-action-button camera" onclick="toggleVideo()" id="video-icon">
                        <i class="fas fa-video"></i>
                    </button>
                    <?php endif; ?>

                    <button class="video-action-button maximize">
                        <i class="fa-solid fa-expand"></i>
                    </button>

                    <button class="video-action-button endcall" id="leave">Leave</button>


                    <div class="video-call-actions ">
                    </div>
                </div>
            </div>
            <div class="app-main col-md-9 col-sm-12 order-sm-0">
                <div class="video-call-wrapper shadow">
                    <div class="video-participant">
                        <div class="participant-actions">
                            
                        </div>

                        <a href="javascript:void(0);" class="name-tag" id="local-player-name"></a>
                        <div id="local-player" class="player"></div>
                        <?php if(astroauthcheck()['profile']): ?>
                            <img src="/<?php echo e(astroauthcheck()['profile']); ?>" alt="participant">
                        <?php else: ?>
                            <img src="<?php echo e(asset('public/frontend/astrowaycdn/dashaspeaks/web/content/images/blank-profile.png')); ?>"
                                alt="participant">
                        <?php endif; ?>
                    </div>
                    <div class="video-participant">
                        <div class="participant-actions">
                            
                        </div>
                        <a href="javascript:void(0);" class="name-tag" id="remote-player-name"></a>
                        <div id="remote-playerlist"></div>
                        <?php if($getUser['recordList'][0]['profile']): ?>
                            <img src="/<?php echo e($getUser['recordList'][0]['profile']); ?>" alt="participant">
                        <?php else: ?>
                            <img src="<?php echo e(asset('public/frontend/astrowaycdn/dashaspeaks/web/content/images/blank-profile.png')); ?>"
                                alt="participant">
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('button.mode-switch').click(function() {
                $('body').toggleClass('dark');
            });

            $(".btn-close-right").click(function() {
                $(".right-side").removeClass("show");
                $(".expand-btn").addClass("show");
            });

            $(".expand-btn").click(function() {
                $(".right-side").addClass("show");
                $(this).removeClass("show");
            });
        });

        function endCall() {
            toastr.success('Call Ended Successfully');
            window.location.href = "<?php echo e(route('front.astrologerindex')); ?>";
        }
    </script>
    <script src="<?php echo e(asset('public/frontend/agora/AgoraRTC_N-4.20.2.js')); ?>"></script>
    <script src="<?php echo e(asset('public/frontend/agora/index.js')); ?>"></script>


    <script>
        $(document).ready(function() {
            var callDuration = <?php echo e($callrequest->call_duration); ?>;
            var timerInterval;
            var statusCheckInterval;

            $("#local-player-name").text("<?php echo e(astroauthcheck()['name']); ?>");
            $("#remote-player-name").text("<?php echo e($getUser['recordList'][0]['name']); ?>");

            function fetchCallStatus() {
                $.ajax({
                    url: '<?php echo e(route('front.callStatus', ['callId' => $callrequest->id])); ?>',
                    type: 'GET',
                    success: function(response) {
                        if (response.call.callStatus === 'Confirmed') {
                            var updateTime = new Date(response.call.updated_at)
                        .getTime(); // Use updated_at from the response
                            startTimer(updateTime);
                            clearInterval(statusCheckInterval);
                        }
                    }
                });
            }

            function startTimer(updateTime) {
                var currentTime = new Date().getTime();
                var elapsedTime = Math.floor((currentTime - updateTime) / 1000);
                var remainingTime = callDuration - elapsedTime;

                function updateTimer() {
                    var minutes = Math.floor(remainingTime / 60);
                    var seconds = remainingTime % 60;

                    var formattedTime = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') +
                        seconds;
                    document.getElementById('remainingTime').innerHTML = formattedTime;
                }

                // Initial display
                updateTimer();

                timerInterval = setInterval(function() {
                    remainingTime--;
                    if (remainingTime < 0) {
                        remainingTime = 0;
                    }
                    updateTimer();

                    if (remainingTime <= 0) {
                        endCall();
                        clearInterval(timerInterval);
                    }
                }, 1000);
            }


            // Initial status check
            fetchCallStatus();
            // Check the status every second
            statusCheckInterval = setInterval(fetchCallStatus, 2000);

            // Initial display of remaining time
            document.getElementById('remainingTime').innerHTML = formatTime(callDuration);

            function formatTime(seconds) {
                var minutes = Math.floor(seconds / 60);
                seconds = seconds % 60;
                return (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.astrologers.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/resources/views/frontend/astrologers/pages/astrologer-callpage.blade.php ENDPATH**/ ?>