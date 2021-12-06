<?php
$plugin_data = get_plugin_data( __FILE__ );
$plugin_name = $plugin_data['Name'];
?>

<h1>Керування тестами</h1>
<div>
    Тут можна буде додавати, відключати, видаляти тести і проводити якісь інші дії
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
                <textarea class="form-control" id="new-test_description" type="textarea" name="test_description" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-primary">Додати новий тест</button>
    </form>
</div>

<?php
if (!empty( $tests )) {
    ?>

    <table class="table table-striped table-sm mt-3">
        <thead class="thead-dark">
            <th scope="col">Назва тесту</th>
            <th scope="col">Опис тесту</th>
            <th scope="col">Керування тестом</th>
        </thead>
        <tbody>

            <?php
            foreach ($tests as $test) {
                ?>
                <tr data-id="<?php echo $test['test_id']; ?>">
                    <td scope="row" class="test-name">
	                    <?php
	                        echo $test['test_name'];
	                    ?>
                    </td>
                    <td scope="row" class="test-description">
		                <?php
		                echo $test['test_description'];
		                ?>
                    </td>
                    <td>
                        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" class="form-inline">
                            <input type="hidden" name="action" value="remove_test"/>
                            <input type="hidden" name="id" value="<?php echo $test['test_id'] ?>">
                            <div class="form-check justify-content-start">
                                <input class="form-check-input test-activation" type="checkbox" value="" id="checkbox_<?php echo $test['test_id']; ?>" <?php echo $test['is_active'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="checkbox_<?php echo $test['test_id']; ?>">
                                    Тест активний
                                </label>
                            </div>
                            <div class="ml-auto test-actions">
                                <button type="button" class="btn btn-sm no-border btn-outline-primary <?php echo $this->setClassNamePrefix("edit-test") ?>">
                                <span class="material-icons">
                                    edit
                                </span>
                                </button>
                                <button type="submit" class="btn btn-sm no-border btn-outline-danger">
                                    <span class="material-icons">
                                        delete
                                    </span>
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>

        </tbody>
    </table>

    <?php
}
?>
