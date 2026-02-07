<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-person-plus-fill me-2"></i>Add New Student
                </h3>
                <p class="text-muted mb-0">Complete the form below to enroll your child</p>
            </div>
            <a href="dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        <!-- Error Messages -->
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="add-student" method="POST">
            <!-- Personal Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-vcard me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                LRN (Learner Reference Number) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="lrn" class="form-control"
                                placeholder="123456789012"
                                pattern="[0-9]{12}"
                                maxlength="12"
                                value="<?= htmlspecialchars($old['lrn'] ?? '') ?>"
                                required>
                            <small class="text-muted">12-digit number</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_of_birth" class="form-control"
                                value="<?= htmlspecialchars($old['date_of_birth'] ?? '') ?>"
                                max="<?= date('Y-m-d') ?>"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                First Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="first_name" class="form-control"
                                placeholder="Juan"
                                value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control"
                                placeholder="Santos"
                                value="<?= htmlspecialchars($old['middle_name'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Last Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="last_name" class="form-control"
                                placeholder="Dela Cruz"
                                value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Name Extension</label>
                            <select name="name_extension" class="form-select">
                                <option value="">None</option>
                                <option value="Jr." <?= ($old['name_extension'] ?? '') === 'Jr.' ? 'selected' : '' ?>>Jr.</option>
                                <option value="Sr." <?= ($old['name_extension'] ?? '') === 'Sr.' ? 'selected' : '' ?>>Sr.</option>
                                <option value="II" <?= ($old['name_extension'] ?? '') === 'II' ? 'selected' : '' ?>>II</option>
                                <option value="III" <?= ($old['name_extension'] ?? '') === 'III' ? 'selected' : '' ?>>III</option>
                                <option value="IV" <?= ($old['name_extension'] ?? '') === 'IV' ? 'selected' : '' ?>>IV</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Gender <span class="text-danger">*</span>
                            </label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?= ($old['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($old['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control"
                                placeholder="City/Municipality, Province"
                                value="<?= htmlspecialchars($old['place_of_birth'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Mother Tongue</label>
                            <input type="text" name="mother_tongue" class="form-control"
                                placeholder="e.g., Tagalog, Bisaya"
                                value="<?= htmlspecialchars($old['mother_tongue'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Religion</label>
                            <input type="text" name="religion" class="form-control"
                                placeholder="e.g., Roman Catholic"
                                value="<?= htmlspecialchars($old['religion'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Indigenous People</label>
                            <input type="text" name="indigenous_people" class="form-control"
                                placeholder="If applicable"
                                value="<?= htmlspecialchars($old['indigenous_people'] ?? '') ?>">
                            <small class="text-muted">Leave blank if not applicable</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-house-door me-2"></i>Address Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">House Number</label>
                            <input type="text" name="house_number" class="form-control"
                                placeholder="Block/Lot/House No."
                                value="<?= htmlspecialchars($old['house_number'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Street Name</label>
                            <input type="text" name="street_name" class="form-control"
                                placeholder="Street/Subdivision Name"
                                value="<?= htmlspecialchars($old['street_name'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Barangay <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="barangay" class="form-control"
                                placeholder="Barangay name"
                                value="<?= htmlspecialchars($old['barangay'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                City/Municipality <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city_municipality" class="form-control"
                                placeholder="City or Municipality"
                                value="<?= htmlspecialchars($old['city_municipality'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Province <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="province" class="form-control"
                                placeholder="Province"
                                value="<?= htmlspecialchars($old['province'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Region</label>
                            <select name="region" class="form-select">
                                <option value="">Select Region</option>
                                <option value="NCR" <?= ($old['region'] ?? '') === 'NCR' ? 'selected' : '' ?>>NCR - National Capital Region</option>
                                <option value="CAR" <?= ($old['region'] ?? '') === 'CAR' ? 'selected' : '' ?>>CAR - Cordillera Administrative Region</option>
                                <option value="Region I" <?= ($old['region'] ?? '') === 'Region I' ? 'selected' : '' ?>>Region I - Ilocos Region</option>
                                <option value="Region II" <?= ($old['region'] ?? '') === 'Region II' ? 'selected' : '' ?>>Region II - Cagayan Valley</option>
                                <option value="Region III" <?= ($old['region'] ?? '') === 'Region III' ? 'selected' : '' ?>>Region III - Central Luzon</option>
                                <option value="Region IV-A" <?= ($old['region'] ?? '') === 'Region IV-A' ? 'selected' : '' ?>>Region IV-A - CALABARZON</option>
                                <option value="Region IV-B" <?= ($old['region'] ?? '') === 'Region IV-B' ? 'selected' : '' ?>>Region IV-B - MIMAROPA</option>
                                <option value="Region V" <?= ($old['region'] ?? '') === 'Region V' ? 'selected' : '' ?>>Region V - Bicol Region</option>
                                <option value="Region VI" <?= ($old['region'] ?? '') === 'Region VI' ? 'selected' : '' ?>>Region VI - Western Visayas</option>
                                <option value="Region VII" <?= ($old['region'] ?? '') === 'Region VII' ? 'selected' : '' ?>>Region VII - Central Visayas</option>
                                <option value="Region VIII" <?= ($old['region'] ?? '') === 'Region VIII' ? 'selected' : '' ?>>Region VIII - Eastern Visayas</option>
                                <option value="Region IX" <?= ($old['region'] ?? '') === 'Region IX' ? 'selected' : '' ?>>Region IX - Zamboanga Peninsula</option>
                                <option value="Region X" <?= ($old['region'] ?? '') === 'Region X' ? 'selected' : '' ?>>Region X - Northern Mindanao</option>
                                <option value="Region XI" <?= ($old['region'] ?? '') === 'Region XI' ? 'selected' : '' ?>>Region XI - Davao Region</option>
                                <option value="Region XII" <?= ($old['region'] ?? '') === 'Region XII' ? 'selected' : '' ?>>Region XII - SOCCSKSARGEN</option>
                                <option value="Region XIII" <?= ($old['region'] ?? '') === 'Region XIII' ? 'selected' : '' ?>>Region XIII - Caraga</option>
                                <option value="BARMM" <?= ($old['region'] ?? '') === 'BARMM' ? 'selected' : '' ?>>BARMM - Bangsamoro Autonomous Region</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Zip Code</label>
                            <input type="text" name="zip_code" class="form-control"
                                placeholder="4000"
                                maxlength="4"
                                value="<?= htmlspecialchars($old['zip_code'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Parent/Guardian Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Father's Information -->
                    <h6 class="text-primary mb-3 border-bottom pb-2">Father's Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Father's Full Name</label>
                            <input type="text" name="father_name" class="form-control"
                                placeholder="Full name"
                                value="<?= htmlspecialchars($old['father_name'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Occupation</label>
                            <input type="text" name="father_occupation" class="form-control"
                                placeholder="Job/Profession"
                                value="<?= htmlspecialchars($old['father_occupation'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Contact Number</label>
                            <input type="tel" name="father_contact" class="form-control"
                                placeholder="09XXXXXXXXX"
                                pattern="09[0-9]{9}"
                                maxlength="11"
                                value="<?= htmlspecialchars($old['father_contact'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Mother's Information -->
                    <h6 class="text-primary mb-3 border-bottom pb-2">Mother's Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Mother's Full Name</label>
                            <input type="text" name="mother_name" class="form-control"
                                placeholder="Full name"
                                value="<?= htmlspecialchars($old['mother_name'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Occupation</label>
                            <input type="text" name="mother_occupation" class="form-control"
                                placeholder="Job/Profession"
                                value="<?= htmlspecialchars($old['mother_occupation'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Contact Number</label>
                            <input type="tel" name="mother_contact" class="form-control"
                                placeholder="09XXXXXXXXX"
                                pattern="09[0-9]{9}"
                                maxlength="11"
                                value="<?= htmlspecialchars($old['mother_contact'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <h6 class="text-primary mb-3 border-bottom pb-2">Guardian/Primary Contact</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Guardian's Name</label>
                            <input type="text" name="guardian_name" class="form-control"
                                placeholder="Full name"
                                value="<?= htmlspecialchars($old['guardian_name'] ?? '') ?>">
                            <small class="text-muted">Person responsible for the child</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Relationship <span class="text-danger">*</span>
                            </label>
                            <select name="guardian_relationship" class="form-select" required>
                                <option value="">Select Relationship</option>
                                <option value="Parent" <?= ($old['guardian_relationship'] ?? '') === 'Parent' ? 'selected' : '' ?>>Parent</option>
                                <option value="Legal Guardian" <?= ($old['guardian_relationship'] ?? '') === 'Legal Guardian' ? 'selected' : '' ?>>Legal Guardian</option>
                                <option value="Grandparent" <?= ($old['guardian_relationship'] ?? '') === 'Grandparent' ? 'selected' : '' ?>>Grandparent</option>
                                <option value="Aunt/Uncle" <?= ($old['guardian_relationship'] ?? '') === 'Aunt/Uncle' ? 'selected' : '' ?>>Aunt/Uncle</option>
                                <option value="Sibling" <?= ($old['guardian_relationship'] ?? '') === 'Sibling' ? 'selected' : '' ?>>Sibling</option>
                                <option value="Other" <?= ($old['guardian_relationship'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Occupation</label>
                            <input type="text" name="guardian_occupation" class="form-control"
                                placeholder="Job/Profession"
                                value="<?= htmlspecialchars($old['guardian_occupation'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>School Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Enrollment Type</label>
                            <select name="enrollment_type" class="form-select">
                                <option value="New" <?= ($old['enrollment_type'] ?? 'New') === 'New' ? 'selected' : '' ?>>New Student</option>
                                <option value="Continuing" <?= ($old['enrollment_type'] ?? '') === 'Continuing' ? 'selected' : '' ?>>Continuing Student</option>
                                <option value="Transferee" <?= ($old['enrollment_type'] ?? '') === 'Transferee' ? 'selected' : '' ?>>Transferee</option>
                                <option value="Returnee" <?= ($old['enrollment_type'] ?? '') === 'Returnee' ? 'selected' : '' ?>>Returnee</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Previous School</label>
                            <input type="text" name="previous_school" class="form-control"
                                placeholder="School name (if transferee)"
                                value="<?= htmlspecialchars($old['previous_school'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Previous Grade Level</label>
                            <select name="previous_grade_level" class="form-select">
                                <option value="">Select if applicable</option>
                                <option value="Kinder" <?= ($old['previous_grade_level'] ?? '') === 'Kinder' ? 'selected' : '' ?>>Kinder</option>
                                <option value="Grade 1" <?= ($old['previous_grade_level'] ?? '') === 'Grade 1' ? 'selected' : '' ?>>Grade 1</option>
                                <option value="Grade 2" <?= ($old['previous_grade_level'] ?? '') === 'Grade 2' ? 'selected' : '' ?>>Grade 2</option>
                                <option value="Grade 3" <?= ($old['previous_grade_level'] ?? '') === 'Grade 3' ? 'selected' : '' ?>>Grade 3</option>
                                <option value="Grade 4" <?= ($old['previous_grade_level'] ?? '') === 'Grade 4' ? 'selected' : '' ?>>Grade 4</option>
                                <option value="Grade 5" <?= ($old['previous_grade_level'] ?? '') === 'Grade 5' ? 'selected' : '' ?>>Grade 5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Submit -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I certify that all information provided is true and correct to the best of my knowledge.
                            I understand that providing false information may result in the rejection of this enrollment application.
                        </label>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Next Steps:</strong> After submitting this form, you will be redirected to upload the required documents
                        (Birth Certificate, Report Card, etc.) for enrollment verification.
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-end mb-4">
                <a href="guardian-dashboard" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Save and Continue
                </button>
            </div>
        </form>
    </div>
</div>