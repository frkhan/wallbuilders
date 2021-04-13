<div class="add_note">
    <p>
        <label for="add_note">Add note</label>
    </p>
    <textarea type="text" name="add_note" id="add_note" class="input-text" cols="20" rows="5"></textarea>
    <br>
    <button type="submit" class="add_note button">Add Note</button>
</div>

<hr><br>
<ul class="timeline">
    <?php foreach($request->getTimeline() as $item): ?>
        <li class="timeline-milestone timeline-start">
            <div class="timeline-action">
                <h2 class="title"><?= $item->title ?></h2>
                <span class="date"><?= $item->postDate('g:ia F j, Y') ?></span>
                <div class="content">
                    <?= $item->content ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>