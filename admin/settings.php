<?php
defined('ABSPATH') || exit;
$subpage = $_GET['subpage'] ?? '';

//switch ($subpage) {
//    case 'logs':
//        include __DIR__ . '/logs.php';
//        return;
//    case 'view':
//        include __DIR__ . '/view.php';
//        return;
//}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_referer('bulk-abilities');
    $settings = $_POST['data'];
    update_option('assistant', $settings ?? []);
}

$settings = get_option('assistant', []);
$enabled_abilities = $settings['abilities'] ?? [];
$provider = $settings['provider'] ?? 'mistral';

class Abilities_List_Table extends WP_List_Table {

    var $enabled_abilities;

    public function __construct($enabled_abilities) {
        parent::__construct([
            'singular' => 'ability', // Singular name of the listed records.
            'plural' => 'abilities', // Plural name of the listed records.
            'ajax' => false, // Does this table support ajax?
        ]);

        $this->enabled_abilities = $enabled_abilities;
    }

    /**
     * Defines the columns for our list table.
     *
     * @return array An associative array of column headers.
     */
    public function get_columns() {
        $columns = [
            'cb' => '<input type="checkbox">',
            'name' => 'Name',
            'label' => 'Label',
            'description' => 'Description',
        ];
        return $columns;
    }

    /**
     * Prepares the data for the list table.
     * This is where you would fetch data from a database, file, or API.
     */
    public function prepare_items() {

        // TODO: Move outside and pass abilities with the constructor
        if (!function_exists('wp_get_abilities')) {
            $this->items = [];
            return;
        }

        $abilities = wp_get_abilities();

        // Define columns and sortable columns (if needed).
        $columns = $this->get_columns();
        $hidden = []; // You can specify columns to hide here.
        $sortable = []; // You can specify sortable columns here.
        $this->_column_headers = [$columns, $hidden, $sortable];

        // This is where you would implement pagination logic.
        $per_page = 20; // Number of items to display per page.
        $current_page = $this->get_pagenum();
        $total_items = count($abilities);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
        ]);

        // Slice the data for the current page.
        $this->items = array_slice($abilities, (($current_page - 1) * $per_page), $per_page);
    }

    /**
     * @param \WP_Ability $item
     */
    public function column_cb($item) {
        return '<input type="checkbox" name="data[abilities][]" value="' . esc_attr($item->get_name()) . '"'
                . (in_array($item->get_name(), $this->enabled_abilities) ? 'checked' : '')
                . '>';
    }

    /**
     * Handles the display of a single column's data.
     * This is the default handler for all columns without a dedicated method.
     *
     * @param \WP_Ability $item        A single item from the data array.
     * @param string $column_name The name of the current column.
     * @return string The content to display for the column.
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'name':
                return esc_html($item->get_name());
            case 'description':
                return esc_html($item->get_description());
            case 'label':
                return esc_html($item->get_label());
            default:
                return '?';
        }
    }
}

$table = new Abilities_List_Table($enabled_abilities);
$table->prepare_items();
?>
<style>
    .key {
        width: 400px;
        font-family: monospace;
    }
</style>
<div class="wrap">
    <h2>Settings</h2>
<!--    <p>
        <a href="?page=monitor-abilities">List</a> | <a href="?page=monitor-abilities&subpage=logs">Logs</a>
    </p>-->

    <form method="post">

        <h3>Abilities</h3>
        <p>This configuration does not work!</p>

        <?php $table->display(); ?>
        <p><button name="save">Save</button></p>

        <h3>LLM Providers</h3>
        <table class="widefat">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Model</th>
                    <th>API Key</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="radio" name="data[provider]" value="mistral" <?php echo $provider === 'mistral' ? 'checked' : ''; ?>>
                    </td>
                    <td>
                        Mistral AI
                    </td>
                    <td>
                        <input type="text" name="data[mistral_model]" class="model" value="<?php echo esc_attr($settings['mistral_model'] ?? ''); ?>" placeholder="mistral-medium-2508">
                    </td>
                    <td>
                        <input type="text" name="data[mistral_key]" class="key" value="<?php echo esc_attr($settings['mistral_key'] ?? ''); ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" name="data[provider]" value="openai" <?php echo $provider === 'openai' ? 'checked' : ''; ?>>
                    </td>
                    <td>
                        Open AI
                    </td>
                    <td>
                        <input type="text" name="data[openai_model]" class="model" value="<?php echo esc_attr($settings['openai_model'] ?? ''); ?>" placeholder="gpt-5-nano">
                    </td>
                    <td>
                        <input type="text" name="data[openai_key]" class="key" value="<?php echo esc_attr($settings['openai_key'] ?? ''); ?>">
                    </td>
                </tr>
            </tbody>
        </table>
        <p><button name="save">Save</button></p>

    </form>

    <pre><?php echo esc_html(print_r(get_option('assistant'), true)); ?></pre>
</div>