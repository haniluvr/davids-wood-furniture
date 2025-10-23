# S3 Bucket Setup Guide for David's Wood Furniture

This guide will help you set up Amazon S3 storage for your Laravel application.

## Prerequisites

- AWS Account
- AWS CLI installed (optional, for advanced users)
- PHP 8.1+ (you're using PHP 8.4.13 ✅)

## Step 1: Create S3 Bucket

1. **Log into AWS Console** at https://console.aws.amazon.com
2. **Navigate to S3 service**
3. **Click "Create bucket"**
4. **Configure your bucket:**
   - **Bucket name**: `davids-wood-furniture-storage` (must be globally unique)
   - **Region**: Choose your preferred region (e.g., `us-east-1`)
   - **Object Ownership**: ACLs disabled (recommended)
   - **Block Public Access**: Keep all settings enabled for security
   - **Bucket Versioning**: Enable (recommended for file management)
   - **Default encryption**: Enable with AES-256

## Step 2: Create IAM User

1. **Go to IAM Console** in AWS
2. **Create a new user:**
   - Username: `davids-wood-s3-user`
   - Access type: Programmatic access
3. **Attach policies:**
   - Create a custom policy with this JSON:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "s3:GetObject",
                "s3:PutObject",
                "s3:DeleteObject",
                "s3:ListBucket"
            ],
            "Resource": [
                "arn:aws:s3:::davids-wood-furniture-storage",
                "arn:aws:s3:::davids-wood-furniture-storage/*"
            ]
        }
    ]
}
```

4. **Save the Access Key ID and Secret Access Key** - you'll need these for your environment variables.

## Step 3: Configure Environment Variables

### For Local Development

Update your `.env` file with:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key_here
AWS_SECRET_ACCESS_KEY=your_secret_key_here
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=davids-wood-furniture-storage
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### For Production

Add these variables to your GitHub Secrets or deployment environment:

- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `AWS_DEFAULT_REGION`
- `AWS_BUCKET`
- `FILESYSTEM_DISK=s3`

## Step 4: Test S3 Connection

Run the test script to verify your S3 setup:

```bash
php test-s3-connection.php
```

This will:
- Test S3 connection
- Upload a test file
- Retrieve the file
- Delete the test file
- List bucket contents

## Step 5: Update Your Application Code

Your Laravel application is already configured to use S3. The filesystem configuration in `config/filesystems.php` includes:

```php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    'throw' => false,
    'report' => false,
],
```

## Step 6: Using S3 in Your Application

### Upload Files

```php
use Illuminate\Support\Facades\Storage;

// Upload a file
Storage::disk('s3')->put('images/product.jpg', $fileContents);

// Upload from uploaded file
Storage::disk('s3')->putFile('images', $request->file('image'));
```

### Retrieve Files

```php
// Get file contents
$contents = Storage::disk('s3')->get('images/product.jpg');

// Get file URL
$url = Storage::disk('s3')->url('images/product.jpg');
```

### Delete Files

```php
// Delete a file
Storage::disk('s3')->delete('images/product.jpg');
```

## Security Best Practices

1. **Never commit AWS credentials** to version control
2. **Use IAM roles** in production when possible
3. **Enable bucket encryption**
4. **Set up CloudTrail** for audit logging
5. **Use least privilege principle** for IAM policies

## Troubleshooting

### Common Issues

1. **Access Denied**: Check IAM permissions
2. **Bucket not found**: Verify bucket name and region
3. **Invalid credentials**: Verify Access Key ID and Secret Access Key

### Debug Commands

```bash
# Check AWS configuration
aws configure list

# Test S3 access
aws s3 ls s3://davids-wood-furniture-storage
```

## Cost Optimization

1. **Set up lifecycle policies** to move old files to cheaper storage
2. **Enable compression** for text files
3. **Use CloudFront** for better performance and reduced S3 costs
4. **Monitor usage** with AWS Cost Explorer

## Next Steps

1. Set up CloudFront CDN for better performance
2. Configure backup strategies
3. Set up monitoring and alerts
4. Implement file versioning policies

## Support

If you encounter issues:

1. Check AWS CloudTrail logs
2. Verify IAM permissions
3. Test with AWS CLI
4. Review Laravel logs for detailed error messages

---

**Note**: This setup uses PHP 8.4.13, which is fully supported by AWS SDK for PHP. The AWS SDK supports PHP 8.1+ as confirmed by the [AWS blog post](https://aws.amazon.com/blogs/developer/announcing-the-end-of-support-for-php-runtimes-8-0-x-and-below-in-the-aws-sdk-for-php/).
