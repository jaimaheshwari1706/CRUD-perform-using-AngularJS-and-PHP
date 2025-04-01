import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserserviceService {

  constructor(private http: HttpClient) { }
  private apiUrl = 'http://localhost/Angularform/index.php';
  private apiUrl2 = 'http://localhost/Angularform/form.php';

  form(create: any) {
    let hobbiesValue = create.hobbies.toString();
    console.log('Hobbies:', hobbiesValue);
    create.hobbies = hobbiesValue;

    let genderValue = create.gender.toString();
    console.log('Gender:', genderValue);
    create.gender = genderValue;

    const filePath = create.file;
    if (filePath) {
      const fileName = filePath.split(/[\\/]/).pop();
      console.log('File Name:', fileName);
      create.file = fileName;
      // this.create.get('file').setValue(fileName);
  
      console.log('File Path:', filePath);
    }

    let data = new FormData();
    console.log('Form Data:', create);
    console.log('Type:', typeof create);

    data.append('Arrar', JSON.stringify(create));
    return this.http.post(this.apiUrl2, data);
  }

  getform() {
    console.log('hi');

    return this.http.get(this.apiUrl2)
    //console.log(data);
    // return data;

  }



}