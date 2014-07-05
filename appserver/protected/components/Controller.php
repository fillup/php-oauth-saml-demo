<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    /**
     * Function sets header for content-type to be application/json, encodes
     * $data into json, echos it out, and exits to prevent any more view to
     * be rendered
     *
     * @param array $data
     * @param integer $status
     */
    public function returnJson($data, $status = 200)
    {
        // Set the content type header.
        header('Content-type: application/json', true, $status);

        // Output the JSON data.
        echo json_encode($data);

        Yii::app()->end();
    }

    /**
     * Accepts an exception and uses the exception message and given status code
     * to return json encoded content with given http status code
     *
     * @param \Exception $error
     * @param integer $status
     */
    public function returnError($error, $status = 400)
    {
        $data = array(
            'success' => 'false',
            'error' => $error->getMessage(),
            'code' => $error->getCode(),
        );

        $this->returnJson($data, $status);
    }
}