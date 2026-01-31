<?php

class Tasks extends Trongate
{

    public function manage()
    {
        $this->trongate_security->make_sure_allowed();

        $data = [
            'tasks' => $this->model->fetch_tasks(),
            'view_module' => 'tasks',
            'view_file' => 'manage'
        ];

        $this->templates->admin($data);
    }

    public function create()
    {
        $this->trongate_security->make_sure_allowed();
        $update_id = segment(3, 'int');
        $submit = post('submit');

        if ($update_id === 0 || $submit === 'Submit') {
            $data = $this->model->get_data_from_post();
        } else {
            $data = $this->model->get_data_from_db($update_id);
        }

        $data['headline'] = ($update_id === 0) ? 'Create Task' : 'Update Task';
        $data['update_id'] = $update_id;
        $data['form_location'] = str_replace('/create', '/submit', current_url());
        $data['view_module'] = 'tasks';
        $data['view_file'] = 'create';
        $data['submit_title'] = ($update_id === 0) ? 'Add New Task' : 'Update Task';
        $this->templates->admin($data);
    }

    public function submit()
    {
        $this->trongate_security->make_sure_allowed();
        $this->validation->set_rules('task_title', 'task title', 'required|min_length[5]|max_length[50]');
        $this->validation->set_rules('description', 'description', 'required|min_length[3]');

        $results = $this->validation->run(); // true or false

        if ($results) {

            //fetch the posted data
            $data = $this->model->get_data_from_post();

            $update_id = segment(3, 'int');

            if ($update_id === 0) {
                //create record
                $this->model->add_task_to_db($data);
                set_flashdata('The new task was successfully created.');
            } else {
                $this->model->update_task_to_db($update_id, $data);
                set_flashdata('The task was successfully updated.');
            };
            redirect('tasks/manage');
        } else {
            $this->create();
        }
    }

    public function confirm_delete()
    {
        $this->trongate_security->make_sure_allowed();
        $update_id = segment(3, 'int');
        $this->model->get_data_from_db($update_id);

        $data = [
            'update_id' => $update_id,
            'view_module' => 'tasks',
            'view_file' => 'confirm_delete',
            'form_location' => str_replace('/confirm_delete', '/submit_confirm_delete', current_url())
        ];
        $this->templates->admin($data);
    }

    public function submit_confirm_delete()
    {
        $this->trongate_security->make_sure_allowed();
        $update_id = (int)post('update_id', true);
        $this->model->delete_task_from_db($update_id);
        set_flashdata('The task was successfully deleted.');
        redirect('tasks/manage');
    }
}
