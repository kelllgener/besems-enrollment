<div class="row justify-content-center">
    <div class="col-xl-10">
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Student added successfully! You can now view them in your list.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?> <li><?= $error ?></li> <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- PERSONAL INFORMATION -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">LRN (12 Digits) <span class="text-danger">*</span></label>
                            <input type="text" name="lrn" class="form-control" pattern="\d{12}" maxlength="12" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Enrollment Type <span class="text-danger">*</span></label>
                            <select name="enrollment_type" class="form-select" required>
                                <option value="New">New</option>
                                <option value="Continuing">Continuing</option>
                                <option value="Transferee">Transferee</option>
                                <option value="Returnee">Returnee</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ext.</label>
                            <input type="text" name="name_extension" class="form-control" placeholder="Jr/III">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother Tongue</label>
                            <input type="text" name="mother_tongue" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Religion</label>
                            <input type="text" name="religion" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADDRESS -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-geo-alt-fill me-2"></i>Address Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">House #</label>
                            <input type="text" name="house_number" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Street Name</label>
                            <input type="text" name="street_name" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Barangay <span class="text-danger">*</span></label>
                            <input type="text" name="barangay" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">City/Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="city_municipality" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Province <span class="text-danger">*</span></label>
                            <input type="text" name="province" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="zip_code" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PARENTS & GUARDIAN -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-people-fill me-2"></i>Family Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Father's Full Name</label>
                            <input type="text" name="father_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother's Full Name</label>
                            <input type="text" name="mother_name" class="form-control">
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <label class="form-label">Guardian Name <span class="text-danger">*</span></label>
                            <input type="text" name="guardian_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Relationship <span class="text-danger">*</span></label>
                            <select name="guardian_relationship" class="form-select" required>
                                <option value="Parent">Parent</option>
                                <option value="Legal Guardian">Legal Guardian</option>
                                <option value="Grandparent">Grandparent</option>
                                <option value="Aunt/Uncle">Aunt/Uncle</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-check2-circle me-2"></i>Register Student
                </button>
                <a href="students.php" class="btn btn-outline-secondary btn-lg">Cancel</a>
            </div>
        </form>

    </div>
</div>
