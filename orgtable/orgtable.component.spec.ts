import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrgtableComponent } from './orgtable.component';

describe('OrgtableComponent', () => {
  let component: OrgtableComponent;
  let fixture: ComponentFixture<OrgtableComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrgtableComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(OrgtableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
