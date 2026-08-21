<?php
if (empty($client_pics)) {
    ?>
    <div class="alert alert-info no-margin">
        <?php _trans('no_pics_found'); ?>
    </div>
    <?php
} else {
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered no-margin">
            <thead>
                <tr>
                    <th><?php _trans('pic_name'); ?></th>
                    <th><?php _trans('pic_position'); ?></th>
                    <th><?php _trans('pic_email'); ?></th>
                    <th><?php _trans('pic_phone'); ?></th>
                    <th><?php _trans('pic_notes'); ?></th>
                    <th class="text-right"><?php _trans('options'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($client_pics as $pic) { ?>
                    <tr>
                        <td><strong><?php _htmlsc($pic->pic_name); ?></strong></td>
                        <td><?php _htmlsc($pic->pic_position); ?></td>
                        <td>
                            <?php if (!empty($pic->pic_email)) { ?>
                                <a href="mailto:<?php echo htmlsc($pic->pic_email); ?>"><?php _htmlsc($pic->pic_email); ?></a>
                            <?php } ?>
                        </td>
                        <td><?php _htmlsc($pic->pic_phone); ?></td>
                        <td><?php echo nl2br(htmlsc($pic->pic_notes ?? '')); ?></td>
                        <td class="text-right">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-default edit_pic"
                                        data-id="<?php echo $pic->client_pic_id; ?>">
                                    <i class="fa fa-edit"></i> <?php _trans('edit'); ?>
                                </button>
                                <button type="button" class="btn btn-danger delete_pic"
                                        data-id="<?php echo $pic->client_pic_id; ?>">
                                    <i class="fa fa-trash-o"></i> <?php _trans('delete'); ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
