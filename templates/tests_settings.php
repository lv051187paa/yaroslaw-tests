<?php
$plugin_data = get_plugin_data( __FILE__ );
$plugin_name = $plugin_data['Name'];
?>

<h1>Керування тестами</h1>
<div>
    <p>
        Тут можна буде додавати, відключати, видаляти тести і проводити якісь інші дії
    </p>
    <p><strong>Для редагування</strong> - клікни на іконці олівця, поля, яке хочеш редагувати.</p>
    <p><strong>Щоб зберегти</strong> відредаговане поле, натисни Enter.</p>
    <p><strong>Щоб відмінити редагування</strong> до того, як збергі їх, натисни Esc</p>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>">
        <input type="hidden" name="action" value="save_test">
        <div class="form-group row">
            <label class="col-sm-1 col-form-label" for="new-test_name">Назва тесту</label>
            <div class="col-sm-11">
                <input type="text" name="test_name" class="form-control" id="new-test_name" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-1" for="new-test_description">Опис тесту</label>
            <div class="col-sm-11">
                <textarea class="form-control" id="new-test_description" type="textarea" name="test_description"
                          rows="3"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-primary">Додати новий тест</button>
    </form>
</div>

<?php
if ( ! empty( $tests ) ) {
	?>

    <table data-list-type="test" class="table table-striped table-editable table-sm mt-3">
        <thead class="thead-dark">
        <th scope="col">Назва тесту</th>
        <th scope="col">Опис тесту</th>
        <th scope="col">Керування тестом</th>
        </thead>
        <tbody>

		<?php
		foreach ( $tests as $test ) {
			$is_archived = (bool) $test['archived'];
			if ( ! $is_archived ) {
				?>
                <tr data-id="<?php echo $test['test_id']; ?>">
                    <td class="test-name">
                        <div class="editable" data-edit-type="text-link" data-editing="false">
                            <a href="<?php echo admin_url( 'admin.php?page=yaroslaw_tests_questions&testId=' . $test['test_id'] ) ?>">
                                <?php echo $test['test_name']; ?>
                            </a>
                        </div>
                        <span class="edit-handler material-icons">
                            edit
                        </span>
                    </td>
                    <td class="test-description">
                        <div class="editable" data-edit-type="text" data-editing="false">
	                        <?php echo $test['test_description']; ?>
                        </div>
                        <span class="edit-handler material-icons">
                            edit
                        </span>
                    </td>
                    <td>
                        <div class="d-flex form-inline">
                            <div class="form-check justify-content-start">
                                <input class="form-check-input test-activation" type="checkbox" value=""
                                       id="checkbox_<?php echo $test['test_id']; ?>" <?php echo $test['is_active'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="checkbox_<?php echo $test['test_id']; ?>">
                                    Тест активний
                                </label>
                            </div>
                            <div class="ml-auto test-actions">
                                <button class="btn btn-sm no-border btn-outline-danger remove-test">
                                    <span class="material-icons">
                                        delete
                                    </span>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
				<?php
			}
		}
		?>

        </tbody>
    </table>

	<?php
}
?>
