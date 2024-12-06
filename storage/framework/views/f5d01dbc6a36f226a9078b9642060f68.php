

<?php $__env->startSection('subhead'); ?>
    <title>Report & Block List</title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('subcontent'); ?>
    <div class="loader"></div>
    <h2 class="intro-y text-lg font-medium mt-10">Astrologer Review</h2>

    <!-- BEGIN: Data List -->
    <?php if($totalRecords > 0): ?>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible withoutsearch">
            <table class="table table-report - mt-7" aria-label="reportBlock">
                <thead class="sticky-top">
                    <tr>
                        <th class="whitespace-nowrap">#</th>
                        <th class="whitespace-nowrap">USERPROFILE</th>
                        <th class="whitespace-nowrap">USERNAME</th>
                        <th class="text-center whitespace-nowrap">ASTROLOGER</th>
                        <th class="text-center whitespace-nowrap">DATE</th>
                        <th class="text-center whitespace-nowrap">Review</th>
                        <th class="text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 0;
                    ?>
                    <?php $__currentLoopData = $reportBlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reportBlock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="intro-x">
                            <td><?php echo e(++$no); ?></td>

                            <td>
                                <div class="flex">
                                    <div class="w-10 h-10 image-fit zoom-in">
                                        <img class="rounded-full" src="/<?php echo e($reportBlock->profile); ?>"
                                            onerror="this.onerror=null;this.src='/build/assets/images/person.png';"
                                            alt="Astrologer image" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-medium whitespace-nowrap">
                                    <?php echo e($reportBlock->userName ? $reportBlock->userName : 'user'); ?> -
                                    <?php echo e($reportBlock->contactNo); ?></div>

                            </td>
                            <td class="text-center"><?php echo e($reportBlock->astrologerName); ?> -
                                <?php echo e($reportBlock->astrologerContactNo); ?></td>
                            <td class="text-center"><?php echo e(date('d-m-Y', strtotime($reportBlock->created_at))); ?></td>
                            <td class="text-center"><?php echo e($reportBlock->review); ?></td>
                            <td class="text-center"> <a id="editbtn" href="javascript:;"
                                    onclick="delbtn(<?php echo e($reportBlock->id); ?>)" class="flex items-center text-danger"
                                    data-tw-target="#delete-confirmation-modal" data-tw-toggle="modal"><i
                                        data-lucide="trash-2" class="editbtn w-4 h-4 mr-1"></i>Delete</a></td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class="d-inline text-slate-500 pagecount">Showing <?php echo e($start); ?> to <?php echo e($end); ?> of
            <?php echo e($totalRecords); ?> entries</div>
        <div class="d-inline addbtn intro-y col-span-12">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    <li class="page-item <?php echo e($page == 1 ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e(route('reportBlock', ['page' => $page - 1])); ?>">
                            <i class="w-4 h-4" data-lucide="chevron-left"></i>
                        </a>
                    </li>
                    <?php for($i = 0; $i < $totalPages; $i++): ?>
                        <li class="page-item <?php echo e($page == $i + 1 ? 'active' : ''); ?> ">
                            <a class="page-link"
                                href="<?php echo e(route('reportBlock', ['page' => $i + 1])); ?>"><?php echo e($i + 1); ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo e($page == $totalPages ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e(route('reportBlock', ['page' => $page + 1])); ?>">
                            <i class="w-4 h-4" data-lucide="chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php else: ?>
        <div class="intro-y mt-5" style="height:100%">
            <div style="display:flex;align-items:center;height:100%;">
                <div style="margin:auto">
                    <img src="/build/assets/images/nodata.png" style="height:290px" alt="noImage">
                    <h3 class="text-center">No Data Available</h3>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- END: Pagination -->
    </div>
    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>

                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-slate-500 mt-2">Do you really want to delete these records? <br>This process
                            cannot be undone.</div>
                    </div>

                    <form action="<?php echo e(route('deleteReview')); ?> " method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <input type="hidden" id="del_id" name="del_id">
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                            <button class="btn btn-danger w-24"><?php echo method_field('DELETE'); ?>Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(window).on('load', function() {
            $('.loader').hide();
        });

        function delbtn($id) {
            $('#del_id').val($id);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('../layout/' . $layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/resources/views/pages/reportBlock.blade.php ENDPATH**/ ?>