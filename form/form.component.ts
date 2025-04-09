import { Component, OnInit } from '@angular/core';
import { UserserviceService } from '../userservice.service';
import { FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  Id: any = 0;
  valueCheck: boolean = true;
  data: any = [];

  constructor(
    private service: UserserviceService,
    private route: ActivatedRoute
  ) {}

  create = new FormGroup({
    id: new FormControl(''),
    name: new FormControl('', Validators.required),
    email: new FormControl('', [Validators.required, Validators.email]),
    phone: new FormControl(''),
    address: new FormControl(''),
    gender: new FormArray([]),
    hobbies: new FormArray([]),
    language: new FormControl(''),
    designation: new FormControl(''),
    file: new FormControl('')
  });

  ngOnInit(): void {
    this.getDesignation();

    const path = this.route.snapshot.url[0]?.path;
    this.valueCheck = path !== 'update';
    console.log('valueCheck:', this.valueCheck);

    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.Id = id;
      this.getdata(this.Id);
    }
  }

  getdata(id: any) {
    this.service.getform(id).subscribe(res => {
      this.data = res[0];
      console.log('Fetched Data:', this.data);

      this.create.patchValue(this.data);
      //language prefill
      this.create.get('language')?.setValue(this.data.language);

      //designation prefill
      this.create.get('designation')?.setValue(this.data.code);

      //gender prefill
      let genders: string[] = Array.isArray(this.data.gender)
        ? this.data.gender
        : this.data.gender.split(',');

      const genderFormArray = this.create.get('gender') as FormArray;
      genderFormArray.clear();

      genders.forEach(gender => {
        genderFormArray.push(new FormControl(gender));
      });

      //hobbies prefill
      let hobbies: string[] = Array.isArray(this.data.hobbies)
        ? this.data.hobbies
        : this.data.hobbies.split(',');

      this.prefillHobbies(hobbies);
    });
  }


  prefillHobbies(hobbies: string[]) {
    const hobbyArray = this.create.get('hobbies') as FormArray;
    hobbyArray.clear();

    if (hobbies) {
      const selectedHobbies = hobbies;
      const checkboxes = document.querySelectorAll('.hobby') as NodeListOf<HTMLInputElement>;

      checkboxes.forEach((checkbox) => {
        if (selectedHobbies.includes(checkbox.value)) {
          checkbox.checked = true;
          hobbyArray.push(new FormControl(checkbox.value));
        } else {
          checkbox.checked = false;
        }
      });

      console.log('Prefilled Hobbies:', hobbyArray.value);
    }
  }

  getDesignation() {
    this.service.getdesignation().subscribe(res => {
      this.data = res;
      console.log('Designation List:', res);
    });
  }

  submit() {
    console.log("Form Data:", this.create.value);
    this.service.form(this.create.value).subscribe(res => {
      console.log('Form submitted successfully', res);
    });
  }

  update() {
    const id = this.route.snapshot.paramMap.get('id');
    this.create.get('id')?.setValue(id);

    this.service.updateForm(this.create.value).subscribe(res => {
      console.log('Updated Successfully', res);
    });
  }

  updateHobbies(event: any) {
    const hobby = event.target.value;
    const isChecked = event.target.checked;

    const hobbyArray = this.create.get('hobbies') as FormArray;

    if (isChecked) {
      if (!hobbyArray.value.includes(hobby)) {
        hobbyArray.push(new FormControl(hobby));
      }
    } else {
      const index = hobbyArray.value.indexOf(hobby);
      if (index !== -1) {
        hobbyArray.removeAt(index);
      }
    }

    console.log('Updated Hobbies:', hobbyArray.value);
  }

  updateGender(event: any) {
    const selectedGenders = Array.from(event.target.selectedOptions, (option: any) => option.value);
    const genderArray = this.create.get('gender') as FormArray;

    genderArray.clear();
    selectedGenders.forEach(gender => genderArray.push(new FormControl(gender)));

    console.log('Updated Gender:', genderArray.value);
  }
}
