<?php

class Employee
{
    public $id;
    public $name;
    public $kana;
    public $sex;
    public $sex_label;
    public $birthdate;
    public $age;
    public $email;
    public $password;
    public $comm_time;
    public $blood_type;
    public $blood_type_label;
    public $married;
    public $branch_id;
    public $branch_name;
    public $quali;
    public $qualies;
    public $image;
    public $prof_text;
    public $delete_flg;

    public function __construct(array $employee)
    {
        $this->id = $employee['id'];
        $this->name = $employee['name'];
        $this->kana = $employee['kana'];
        $this->sex = $employee['sex'];
        $this->sex_label = $this->sexLabel();
        $this->birthdate = $employee['birthdate'];
        $this->age = $this->ageFromBirthday();
        $this->email = $employee['email'];
        $this->password = $employee['password'];
        $this->comm_time = $employee['comm_time'];
        $this->blood_type = $employee['blood_type'];
        $this->blood_type_label = $this->bloodTypeLabel();
        $this->married = $employee['married'];
        $this->branch_id = $employee['branch_id'];
        $this->branch_name = array_key_exists('branch_name', $employee) ? $employee['branch_name'] : null;
        $this->quali = $employee['quali'];
        $this->qualies = explode(',', $employee['quali']);
        $this->image = $employee['image'];
        $this->prof_text = $employee['prof_text'];
        $this->delete_flg = $employee['delete_flg'];
    }

    /**
     * 生年月日から年齢を算出
     *
     * @return float|null
     */
    public function ageFromBirthday(): ?float
    {
        if ($this->birthdate === null) {
            return null;
        }

        $birthday = str_replace("-", "", $this->birthdate);
        $age = floor((date('Ymd') - $birthday) / 10000);
        if ($age === false) {
            return null;
        }

        return $age;
    }

    /**
     * 性別ラベル表示
     *
     * @return ?string
     */
    public function sexLabel(): ?string
    {
        if ($this->sex === null) {
            return null;
        }

        $int_sex = (int)$this->sex;

        if ($int_sex === 1) {
            return '男';
        }

        if ($int_sex === 2) {
            return '女';
        }

        return '不明';
    }

    /**
     * 血液型ラベル表示
     *
     * @return string
     */
    public function bloodTypeLabel(): string
    {
        switch ($this->blood_type) {  
            case 1:
                return "A型";
                break;
            case 2:
                return "B型";
                break;
            case 3:
                return "AB型";
                break;
            case 4:
                return "O型";
                break;
            case 5:
                return "不明";
                break;
            default:
                return "";
        }
    }
}
