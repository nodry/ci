<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Code extends CW_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function test()
	{
		echo "테스트 1234 ";
	}

    /**
     * 단일 코드 그룹 조회 (e.g. post, /code/group, group_id = GENDER)
     */

    public function group()
    {
        $this->tokenCheck();
        $group_id = $this->input->post('group_id');

        $result = $this->codes->getCodeDetails($group_id, 'Y');
        $this->setJsonResponse($result['success'], $result['message'], $result['query']->result_array());
    }

    /**
     * 복수 코드 그룹 조회 (e.g. post, /code/groups, ids=GENDER,PAY_METHOD)
     */

    public function groups()
    {
        $this->tokenCheck();

        $group_ids = $this->input->post('group_ids');
        $result = $this->codes->getCodeMap($group_ids, 'Y');
        $this->setJsonResponse($result['success'], $result['message'], $result['query']->result_array());
    }

    /**
     * 전체 코드 그룹 조회 (e.g. /code/all-groups)
     */

    public function all_groups()
    {
        $this->tokenCheck();

		$group_ids = $this->input->post('group_ids');
        $result = $this->codes->getCodeGroups($group_ids);
        $this->setJsonResponse($result['success'], $result['message'], $result['query']->result_array());
    }


    /**
     * 코드명 단건 조회 (e.g. /code/name, code_id=12 group_id=GENDER code=M)
     */
    public function name()
    {
        $this->tokenCheck();

        $code_id = $this->input->post('code_id');
        $group_id = $this->input->post('group_id');
        $code = $this->input->post('code');

        $result = $this->codes->getCodeName($code_id, $group_id, $code);
        $this->setJsonResponse($result['success'], $result['message'], $result['query']->result_array());
    }


}
