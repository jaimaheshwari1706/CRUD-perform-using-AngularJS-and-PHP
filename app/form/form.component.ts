import { Component, OnInit } from '@angular/core';
import { UserserviceService } from '../userservice.service';
import { FormArray, FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  [x: string]: any;

  constructor(private service: UserserviceService) { }

  ngOnInit(): void { this.getdata()  }

  data:any=[]

  create: any = new FormGroup({
    name: new FormControl(''),
    email: new FormControl(''),
    phone: new FormControl(''),
    address: new FormControl(''),
    gender: new FormArray([]),
    hobbies: new FormArray([]),
    language: new FormControl(''),
    designation: new FormControl(''),
    file: new FormControl('')
  })

  

  getdata(){
    this.service.getform().subscribe(res=>{
      this.data = res;
      console.log('Fetched Data:', this.data);
    })
  }

  submit() {
    console.log("Form Data:", this.create.value);
  
    this.service.form(this.create.value).subscribe(res => {
      console.log('Form submitted successfully', res);
    });
  

  }
  

  updateHobbies(event: any) {
    console.log('event', event);
    const hobby = event.target.value;
    const isChecked = event.target.checked; 
    
    if (hobby) {
      let hobbyArray = this.create.get('hobbies') as FormArray;
  
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
  }

  updateGender(event: any) {
    const selectedGenders = Array.from(event.target.selectedOptions, (option: any) => option.value);
  
    console.log('Selected Genders:', selectedGenders);
  
    let genderFormArray = this.create.get('gender') as FormArray;
  
    genderFormArray.clear();
  
    selectedGenders.forEach((gender) => {
      genderFormArray.push(new FormControl(gender));
    });
  
    console.log('Updated Gender:', genderFormArray.value);
  }


}


