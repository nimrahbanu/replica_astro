

<?php $__env->startSection('content'); ?>
    <section class="blog-page" style="padding-top: 160px !important;">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="blog-details news-slid">
                        <div class="news-text">
                            <div class="blog-hover">
                                <h4><?php echo e($privacy->title); ?></h4>
                            </div>      
                        </div>     
                      
                            <?php echo $privacy->description; ?>

                          
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/astroway.diploy.in/httpdocs/resources/views/privacypolicy.blade.php ENDPATH**/ ?>