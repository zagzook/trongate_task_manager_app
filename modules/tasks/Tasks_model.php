<?php
class Tasks_model extends Model
{
    public function get_data_from_post()
    {
        $data = [
            'task_title' => post('task_title', true),
            'description' => post('description', true),
            'complete' => (int)(bool) post('complete', true)
        ];

        return $data;
    }

    public function add_task_to_db($data)
    {
        $this->db->insert($data, 'tasks');
    }

    public function update_task_to_db($update_id, $data)
    {
        $this->db->update($update_id, $data, 'tasks');
    }

    public function fetch_tasks()
    {
        //fetch all the tasks
        $tasks = $this->db->get('id', 'tasks');

        foreach ($tasks as $key => $task) {
            $complete = (int)$task->complete;

            $tasks[$key]->status = ($complete === 1) ? 'complete' : 'not complete';
        }

        return $tasks;
    }

    public function get_data_from_db($update_id)
    {
        // code here
        $record_obj = $this->db->get_where($update_id, 'tasks');

        if ($record_obj === false) {
            http_response_code(404);
            echo 'Task not found';
            die();
        }

        $tasks = (array)$record_obj;

        return $tasks;
    }

    public function delete_task_from_db($update_id)
    {
        $this->db->delete($update_id, 'tasks');
    }
}
