<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Code_model extends CW_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 전체 코드 그룹 리스트 조회
     */
    public function getCodeGroups($group_ids, $use_yn = 'Y')
    {
        $this->db->from('code_group');
        if ($use_yn !== null) {
            $this->db->where('use_yn', $use_yn);
        }
        $this->db->order_by('group_id', 'ASC');
        $query = $this->db->get();

        return makeResultForQuery(true, '조회 성공', $query);
    }

    /**
     * 특정 코드 그룹에 대한 상세 코드 리스트 조회
     */
    public function getCodeDetails($group_id, $use_yn = 'Y')
    {
        $this->db->from('code_detail');
        $this->db->where('group_id', $group_id);
        if ($use_yn !== null) {
            $this->db->where('use_yn', $use_yn);
        }
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get();
//        return $this->db->get()->result();

        return makeResultForQuery(true, '조회 성공', $query);
    }

    /**
     * 여러 코드 그룹에 대한 상세 코드들을 배열로 반환
     */
    public function getCodeMap($group_ids, $use_yn = 'Y')
    {
        $this->db->from('code_detail');
        $this->db->where_in('group_id', explode(",", $group_ids));
        if ($use_yn !== null) {
            $this->db->where('use_yn', $use_yn);
        }
        $this->db->order_by('group_id ASC, sort_order ASC');
        $query = $this->db->get();

        return makeResultForQuery(true, '조회 성공', $query);
    }

    /**
     * 단일 코드 값으로 이름 조회
     */
    public function getCodeName($code_id, $group_id, $code)
    {
        $this->db->select('code_name');
        $this->db->from('code_detail');
        if ($code_id !== '') {
            $this->db->where('id', $code_id);
        } else {
            $this->db->where('group_id', $group_id);
            $this->db->where('code', $code);
        }

        $query = $this->db->get();

        return makeResultForQuery(true, '조회 성공', $query);

    }
}
