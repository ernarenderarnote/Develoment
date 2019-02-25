<div>
    @lang('labels.moderation_status'):
    @if ($resource->isApproved())
        <div class="badge badge-success">{{ $resource->getModerationStatusName() }}</div>
    @elseif ($resource->isDeclined())
        <div class="badge badge-warning">{{ $resource->getModerationStatusName() }}</div>
    @elseif ($resource->isOnModeration())
        <div class="badge badge-info">{{ $resource->getModerationStatusName() }}</div>
    @else
        <div class="badge badge-secondary">{{ $resource->getModerationStatusName() }}</div>
    @endif
</div>
    
@if ($resource->isDeclined())
    <div>
        @lang('labels.moderation_status_comment'):
        <blockquote>{{ $resource->moderation_status_comment }}</blockquote>
    </div>
@endif
